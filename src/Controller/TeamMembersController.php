<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class TeamMembersController extends AppController {

	public function beforeFilter(\Cake\Event\Event $event) 
	{
		// if ($this->getRequest()->getParam('action') == 'login' || $this->getRequest()->getParam('action') == 'account')
		// 	return;
		// // check for manage_users permission for all other actions
		// if (!$this->appUser->manage_users) {
		// 	$this->redirect(['controller' => 'Threads', 'action' => 'index']);
		// }
	}

	public function index() 
	{
		$users = $this->TeamMembers->find('all')
		->contain(['states'])
		->order(['last_name' => 'ASC']);
		$this->set('users', $users);
	}

	public function add() 
	{
		if (!$this->appUser->manage_users) {
			$this->redirect(['controller' => 'Threads', 'action' => 'index']);
		}

		if ($this->request->getData()) {
			$user = $this->TeamMembers->newEntity($this->request->getData());
			if ($this->TeamMembers->save($user)) {
				return $this->redirect(['action' => 'index']);
			}
		} else {
			$user = $this->TeamMembers->newEntity();
		}
		$this->set('user', $user);
		$this->set('states', $this->TeamMembers->TeamMemberLicenses->States->altList());
	}

	public function edit($id) 
	{
		if (!$this->appUser->manage_users) {
			$this->redirect(['controller' => 'Threads', 'action' => 'index']);
		}

		$user = $this->TeamMembers->find()
		->where(['id' => $id])
		->contain(['TeamMemberLicenses', 'TeamMemberLicenses.States', 'TeamMemberAccounts', 'TeamMemberAccounts.States'])
		->first();

		if ($this->request->getData()) {
			$user = $this->TeamMembers->patchEntity($user, $this->request->getData());
			if ($this->request->getData()['new_password']) {
				$user->password = $this->request->getData()['new_password'];
			}
			if ($this->TeamMembers->save($user)) {
				$this->Flash->success('Account information updated.');
				$this->redirect(['action' => 'edit', $user->id]);
			}
		}
		$this->set('teamMember', $user);
		$this->set('states', $this->TeamMembers->TeamMemberLicenses->States->altList());
		$this->getRequest()->getSession()->write('manageEditing', true);
	}

	public function login()
	{
		$this->viewBuilder()->setLayout('materialize_no_menu');
		$this->getRequest()->getSession()->write('User.id', null);
		$requestedUrl = str_replace('lawhq/', '', $this->getRequest()->getSession()->read('requestedUrl'));
		
		if ($this->request->getData()) {
			$user = $this->TeamMembers->findByUsername($this->request->getData('username'))->first();
			if (!$user || !$user->verifyPassword($this->request->getData('password')) || !$user->active) {
				$this->Flash->set('Invalid username or password');
				return;
			}
			// verify password will update the hash if needed
			if ($user->isDirty()) {
				$this->TeamMembers->save($user);
			}
			$this->getRequest()->getSession()->write('User.id', $user->id);

			if (!$user->password_updated) {
			    $this->redirect(['controller' => 'TeamMembers', 'action' => 'updatePassword']);
			} else if ($requestedUrl && $requestedUrl != '/') {
				$this->redirect($requestedUrl);
			} else {
				$this->redirect(['controller' => 'threads', 'action' => 'index']);
			}
		}
	}

	public function account()
	{
		$teamMember = $this->TeamMembers->find()
		->where(['TeamMembers.id' => $this->appUser->id])
		->contain(['States', 'TeamMemberLicenses', 'TeamMemberLicenses.States', 'TeamMemberAccounts', 'TeamMemberAccounts.States'])
		->first();

		$this->set(compact('teamMember'));
		$this->set('states', $this->TeamMembers->TeamMemberLicenses->States->altList());
		$this->getRequest()->getSession()->write('manageEditing', false);
	}

	public function editInfo()
	{
		$teamMember = $this->TeamMembers->get($this->appUser->id);

		if ($this->request->getData()) {
			$teamMember = $this->TeamMembers->patchEntity($teamMember, $this->request->getData());
			if ($this->TeamMembers->save($teamMember)) {
				$this->redirect(['action' => 'account']);
			}
		}

		$this->set('states', $this->TeamMembers->TeamMemberLicenses->States->altList());
	}

	public function updatePassword()
	{
		$this->viewBuilder()->setLayout('materialize_no_menu');

		if ($this->request->getData()) {
			if ($this->request->getData()['password'] != $this->request->getData()['confirm_password']) {
				$this->Flash->error('Passwords do not match.');
				return;
			}
			if ($this->appUser->verifyPassword($this->request->getData('password'))) {
				$this->Flash->error('Please set a different password.');
				return;
			}
			$this->appUser->password = $this->request->getData()['password'];
			$this->appUser->password_updated = date("Y-m-d H:i:s", strtotime('now'));
			if ($this->TeamMembers->save($this->appUser)) {
				$this->Flash->success('Your password has been updated. Please verify your personal information.');
				return $this->redirect(['action' => 'editInfo']);
			}
			$this->Flash->error('There has been a problem. Please try again.');
		}

		if (!$this->appUser->password_updated) {
		    $this->Flash->error('Set a new password to continue backend use.');
		}
	}

	public function addLicense()
	{		
		if ($this->request->getData()) {
			$data = $this->request->getData();
			if ($this->TeamMembers->TeamMemberLicenses->exists(['team_member_id' => $data['team_member_id'], 'state_id' => $data['state_id'], 'type' => $data['type']])) {
				$this->Flash->error('This account already exists.');
				if ($this->manageEditing) {
					return $this->redirect(['action' => 'edit', 'id' => $data['team_member_id']]);
				}
				return $this->redirect(['action' => 'account']);
			}

			$license = $this->TeamMembers->TeamMemberLicenses->newEntity();
			$license = $this->TeamMembers->TeamMemberLicenses->patchEntity($license, $data);
			if ($this->TeamMembers->TeamMemberLicenses->save($license)) {
				if ($this->manageEditing) {
					$this->redirect(['action' => 'edit', 'id' => $license->team_member_id]);
				}
				$this->redirect(['action' => 'account']);
			}
		}
	}

	public function editLicense($id)
	{
		$license = $this->TeamMembers->TeamMemberLicenses->get($id);
		if (!$this->appUser->manage_users && $license->team_member_id != $this->appUser->id) {
			$this->redirect(['action' => 'account']);
		}

		if ($this->request->getData()) {
			$data = $this->request->getData();
			if ($this->TeamMembers->TeamMemberLicenses->exists(['id !=' => $license->id, 'team_member_id' => $license->team_member_id, 'state_id' => $data['state_id'], 'type' => $data['type']])) {
				$this->Flash->error('This account already exists.');
				$this->redirect(['action' => 'editLicense', $id]);
			}

			$license = $this->TeamMembers->TeamMemberLicenses->patchEntity($license, $data);
			if ($this->TeamMembers->TeamMemberLicenses->save($license)) {
				if ($this->manageEditing) {
					$this->redirect(['action' => 'edit', 'id' => $license->team_member_id]);
				}
				$this->redirect(['action' => 'account']);
			}
		}

		$this->set('states', $this->TeamMembers->TeamMemberLicenses->States->altList());
		$this->set(compact('license'));
	}

	public function deleteLicense($id)
	{
		$license = $this->TeamMembers->TeamMemberLicenses->get($id);
		if ($this->TeamMembers->TeamMemberLicenses->delete($license)) {
			if ($this->manageEditing) {
				$this->redirect(['action' => 'edit', 'id' => $license->team_member_id]);
			}
			$this->redirect(['action' => 'account']);
		}
	}

	public function addAccount()
	{
		if ($this->request->getData()) {
			$data = $this->request->getData();
			$error = false;
			if ($data['account'] == 'pacer' && $this->TeamMembers->TeamMemberAccounts->exists(['team_member_id' => $data['team_member_id'], 'account' => 'pacer'])) {
				$this->Flash->error('A pacer account already exist.');
				$error = true;
			} else if ($this->TeamMembers->TeamMemberAccounts->exists(['team_member_id' => $data['team_member_id'], 'account' => $data['account'], 'state_id' => $data['state_id']])) {
				$this->Flash->error('This account already exists.');
				$error = true;
			}
			if ($error) {
				if ($this->manageEditing) {
					return $this->redirect(['action' => 'edit', 'id' => $data['team_member_id']]);
				}
				return $this->redirect(['action' => 'account']);
			}

			$account = $this->TeamMembers->TeamMemberAccounts->newEntity();
			$account = $this->TeamMembers->TeamMemberAccounts->patchEntity($account, $this->request->getData());
			if ($this->TeamMembers->TeamMemberAccounts->save($account)) {
				if ($this->manageEditing) {
					$this->redirect(['action' => 'edit', 'id' => $account->team_member_id]);
				}
				$this->redirect(['action' => 'account']);
			}
		}
	}

	public function editAccount($id)
	{
		$account = $this->TeamMembers->TeamMemberAccounts->find()
		->where(['TeamMemberAccounts.id' => $id])
		->contain(['States'])->first();

		if (!$this->appUser->manage_users && $account->team_member_id != $this->appUser->id) {
			$this->redirect(['action' => 'account']);
		}

		if ($this->request->getData()) {
			 $account = $this->TeamMembers->TeamMemberAccounts->patchEntity($account, $this->request->getData());
			if ($this->TeamMembers->TeamMemberAccounts->save($account)) {
				if ($this->manageEditing) {
					$this->redirect(['action' => 'edit', 'id' => $account->team_member_id]);
				}
				$this->redirect(['action' => 'account']);
			}
		}

		$this->set(compact('account'));
		$this->set('states', $this->TeamMembers->TeamMemberLicenses->States->altList());
	}

	public function deleteAccount($id)
	{
		$account = $this->TeamMembers->TeamMemberAccounts->get($id);
		if ($this->TeamMembers->TeamMemberAccounts->delete($account)) {
			if ($this->manageEditing) {
				$this->redirect(['action' => 'edit', 'id' => $account->team_member_id]);
			}
			$this->redirect(['action' => 'account']);
		}
	}
}
