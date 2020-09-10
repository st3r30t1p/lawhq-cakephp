<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use App\Lib\Contact;

class ContactsController extends AppController {

	public function index()
	{
		$contacts = $this->Contacts->find();

		if ($this->request->getQuery('q')) {
			$q = $this->request->getQuery('q');
			$contacts->where(["concat(person_first_name, ' ', person_last_name) LIKE '%{$q}%'"])
			->orWhere(["company_name like '%{$q}%'"]);
		}

		if ($this->request->getQuery('type')) {
			$contacts->where(['type' => $this->request->getQuery('type')]);
		}

		$contacts->where(['(company_domestic_foreign = "domestic" OR company_domestic_foreign IS NULL)', 'is_deleted IS' => null]);
		$contacts->contain(['contactEmails', 'contactAddresses', 'contactAddresses.States', 'contactPhoneNumbers'])
		->order(['person_first_name' => 'ASC', 'company_name' => 'ASC', 'company_incorporated_in' => 'ASC']);

		$this->set('contacts', $this->paginate($contacts));
		$this->set('query', $this->request->getQuery());

	}

	public function add()
	{
		$contact = $this->Contacts->newEntity($this->request->getData());

		if ($this->request->getData()) {
			$this->Contacts->patchEntity($contact, $this->request->getData(), [
			    'associated' => ['contactEmails', 'contactAddresses', 'contactDbas', 'contactPhoneNumbers', 'contactWebsites', 'contactRelationships', 'targetRelationships']
			]);

			$this->Contacts->contactRelationships->validator('default')->provider('passed', [
			    'contact_entity_type' => $contact->company_domestic_foreign
			]);
			if ($contact->hasErrors()) {
				$this->Flash->error('Please fix the errors below.');
			}
			
			if ($this->Contacts->save($contact)) {

				if (isset($this->request->getData()['view'])) {
					return $this->redirect(['action' => 'view', 'id' => $contact->id]);
				}

				return $this->redirect(['action' => 'edit', 'id' => $contact->id]);
			}
		}

		$this->set(compact('contact'));
		$this->set('contacts', $this->Contacts->allList());
		$this->set('states', $this->Contacts->States->list());
		$this->set('countries', $this->Contacts->contactAddresses->Countries->list());
	}

	public function edit($id)
	{
		$contact = $this->Contacts->get($id, [
		    'contain' => ['contactEmails', 'contactAddresses', 'contactDbas', 'contactPhoneNumbers', 'contactWebsites', 'contactRelationships', 'targetRelationships']
		]);
		
		$this->Contacts->contactRelationships->validator('default')->provider('passed', [
		    'contact_entity_type' => $contact->company_domestic_foreign
		]);

		if ($this->request->getData()) {
			$contact = $this->Contacts->patchEntity($contact, $this->request->getData(), [
			    'associated' => ['contactEmails', 'contactAddresses', 'contactDbas', 'contactPhoneNumbers', 'contactWebsites', 'contactRelationships', 'targetRelationships']
			]);

			if ($contact->hasErrors()) {
				$this->Flash->error('Please fix the errors below.');
			}
			if ($this->Contacts->save($contact)) {

				if (isset($this->request->getData()['view'])) {
					return $this->redirect(['action' => 'view', 'id' => $contact->id]);
				}

				return $this->redirect(['action' => 'edit', 'id' => $contact->id]);
			}
		} 

		$this->set(compact('contact'));
		$this->set('isContactNew', false);
		$this->set('contactsTable', $this->Contacts);
		$this->set('contacts', $this->Contacts->allList());
		$this->set('states', $this->Contacts->States->list());
		$this->set('countries', $this->Contacts->contactAddresses->Countries->list());
	}

	public function view($id = null)
	{
		$contact = $this->Contacts->get($id, [
			'contain' => ['contactEmails', 'contactAddresses', 'contactAddresses.States', 'contactDbas', 'contactPhoneNumbers', 'contactWebsites', 'contactRelationships', 'targetRelationships', 'contactNotes', 'contactNotes.TeamMembers', 'states']
		]);

		if (empty($contact) || $contact->is_deleted) {
			return $this->redirect(['action' => 'index']);
		}

		$contact->contact_relationships = array_merge($contact->contact_relationships, $contact->target_relationships);
		$contactInfo = new Contact($contact, $this->Contacts);

		$this->set('contactsTable', $this->Contacts);
		$this->set(compact('contact', 'contactInfo'));
		$this->addToHistory('Contact', $contact->id);
	}

	public function delete($id = null)
	{
		$this->viewBuilder()->setLayout('ajax');
		if (empty($id)) { return $this->redirect(['action' => 'index']); }
		$contact = $this->Contacts->get($id);
		$contact->is_deleted = 1;
		if ($this->Contacts->save($contact)) {
			$this->redirect(['action' => 'index']);
		}
	}

	public function spam()
	{
		$rulesTable = TableRegistry::getTableLocator()->get('Rules');
		$contactId = $this->request->getQuery('contact-id');

		$contact = $this->Contacts->find()
		->where(['id' => $contactId])
		->first();

		$rules = $rulesTable->find()
		->where(['contact_id' => $contactId])
		->contain(['Contacts', 'RuleConditionSets', 'RuleConditionSets.RuleAssignments', 'RuleConditionSets.RuleAssignments.ImportedMessages', 'RuleConditionSets.RuleAssignments.ImportedMessages.Threads', 'RuleConditionSets.RuleAssignments.ImportedMessages.Threads.ImportedUsers']);

		$groupedMessages = [];

		foreach ($rules as $rule) {
			foreach ($rule->rule_condition_sets as $set) {
				foreach ($set->rule_assignments as $assignment) {

					if (isset($groupedMessages[$assignment->imported_message->id])) {
						array_push($groupedMessages[$assignment->imported_message->id]['rules'], $rule->id);
					} else {

						$message = [
							'message_id' => $assignment->imported_message->id,
							'thread_id' => $assignment->imported_message->thread_id,
							'received_time' => $assignment->imported_message->received_time,
							'from_phone' => $assignment->imported_message->thread->from_phone,
							'to_phone' => $assignment->imported_message->thread->to_phone,
							'user_name' => $assignment->imported_message->thread->imported_user->name_firstName . ' ' . $assignment->imported_message->thread->imported_user->name_lastName,
							'body' => $assignment->imported_message->formattedBody(),
							'rules' => [$rule->id],
							'matter' => '',
						];

						$groupedMessages[$assignment->imported_message->id] = $message;
					}
				}
			}
		}

		$this->set(compact('contact', 'groupedMessages'));
	}
}		