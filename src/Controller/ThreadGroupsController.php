<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class ThreadGroupsController extends AppController {

	public function index() {
		$threadGroupsTable = TableRegistry::getTableLocator()->get('ThreadGroups');

		$threadGroups = $threadGroupsTable->find()
		->order('ThreadGroups.name');

		// get thread group stats
		$query = $this->ThreadGroups->Threads->find();
		$query->select([
			'thread_group_id',
			'thread_count' => $query->func()->count('*'),
			'user_count' => $query->func()->COUNT('DISTINCT imported_user_id'),
			'msg_count' => $query->func()->sum('imported_msg_rcvd_count'),
			'last_message' => $query->func()->max('last_message_received')
		])
		->group(['thread_group_id']);
		$tgStats = [];
		foreach ($query as $stat) {
			if ($stat->thread_group_id === null) continue;
			$tgStats[$stat->thread_group_id] = $stat;
		}

		$this->set('threadGroups', $threadGroups);
		$this->set('tgStats', $tgStats);
	}

	public function view($id) {
		$threadGroup = $this->ThreadGroups->find()
		->where(['ThreadGroups.id' => $id])
		->contain(['Threads', 'Threads.ImportedUsers', 'ThreadGroupNotes', 'ThreadGroupNotes.TeamMembers'])
		->first();
		$this->set('threadGroup', $threadGroup);

		$threadGroupNote = $this->ThreadGroups->ThreadGroupNotes->newEntity();
		$this->set('threadGroupNote', $threadGroupNote);
		$this->addToHistory('Thread Group', $threadGroup->id);
	}

	public function add() {
		if ($this->request->getData()) {
			$threadGroup = $this->ThreadGroups->newEntity($this->request->getData());
			if ($this->ThreadGroups->save($threadGroup)) {
				return $this->redirect(['action' => 'index']);
			}
		} else {
			$threadGroup = $this->ThreadGroups->newEntity();
		}
		$this->set('threadGroup', $threadGroup);
	}

}