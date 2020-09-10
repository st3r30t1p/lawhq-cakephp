<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class OldMattersController extends AppController {

	public function index() {
		$matters = $this->OldMatters->find()
		->order('OldMatters.name');

		// get thread group stats
		$query = $this->OldMatters->Threads->find();
		$query->select([
			'old_matter_id',
			'thread_count' => $query->func()->count('*'),
			'user_count' => $query->func()->COUNT('DISTINCT imported_user_id'),
			'msg_count' => $query->func()->sum('imported_msg_rcvd_count'),
			'last_message' => $query->func()->max('last_message_received')
		])
		->group(['old_matter_id']);
		$tgStats = [];
		foreach ($query as $stat) {
			if ($stat->matter_id === null) continue;
			$tgStats[$stat->matter_id] = $stat;
		}

		$this->set('matters', $matters);
		$this->set('tgStats', $tgStats);
	}

	public function edit($id) {
		$matter = $this->OldMatters->find()
		->where(['OldMatters.id' => $id])
		->first();

		if ($this->request->getData()) {
			$this->OldMatters->patchEntity($matter, $this->request->getData());
			$this->OldMatters->save($matter);
			return $this->redirect(['action' => 'index']);
		}

		$this->set('matter', $matter);
	}

	public function view($id) {
		$matter = $this->OldMatters->find()
		->where(['OldMatters.id' => $id])
		->contain(['Threads' => ['sort' => ['ImportedUsers.name_lastName', 'ImportedUsers.name_firstName']], 'Threads.ImportedUsers'])
		->first();

		$this->set('matter', $matter);
	}

	public function report($id) {
		$matter = $this->OldMatters->find()
		->where(['OldMatters.id' => $id])
		->contain(['Threads' => ['sort' => ['ImportedUsers.name_lastName', 'ImportedUsers.name_firstName']], 'Threads.ImportedMessages', 'Threads.ImportedUsers'])
		->first();

		$groupedData = [];

		foreach ($matter->threads as $thread) {
			$userId = $thread->imported_user_id;
			if (!isset($groupedData[$userId])) {
				$groupedData[$userId] = [
					'user' => $thread->imported_user,
					'threads' => []
				];
			}
			$groupedData[$userId]['threads'][] = $thread;
		}

		$this->set('matter', $matter);
		$this->set('groupedData', $groupedData);
	}

	public function add() {
		if ($this->request->getData()) {
			$matter = $this->OldMatters->newEntity($this->request->getData());
			if ($this->OldMatters->save($matter)) {
				return $this->redirect(['action' => 'index']);
			}
		} else {
			$matter = $this->OldMatters->newEntity();
		}
		$this->set('matter', $matter);
	}

}