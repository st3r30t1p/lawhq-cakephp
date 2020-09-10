<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class ThreadsController extends AppController {
	public $paginate = [
	    'limit' => 100
	];

	public function index() {
		$where = [];
		if ($this->request->getQuery('userId')) {
			$where['Threads.imported_user_id'] = $this->request->getQuery('userId');
		}
		if ($this->request->getQuery('from')) {
			$where['Threads.from_phone'] = $this->request->getQuery('from');
		}
		if ($this->request->getQuery('to')) {
			$where['Threads.to_phone'] = $this->request->getQuery('to');
		}

		$threads = $this->Threads->find()
		->where($where)
		->order('Threads.modified DESC')
		->contain(['ImportedUsers', 'ThreadGroups', 'Matters', 'OldMatters', 'RuleAssignments']);

		$this->set('threads', $this->paginate($threads));
	}

	public function view($id) {
		$thread = $this->Threads->find()
		->where(['Threads.id' => $id])
		->contain(['ImportedUsers', 'ImportedMessages', 'ThreadNotes', 'ThreadNotes.TeamMembers'])
		->first();
		
		// associate a group
		if ($this->request->getData('thread_group_id') !== null) {
			$thread->set($this->request->getData());
			$this->Threads->save($thread);
		}
		$this->set('thread', $thread);

		$threadNote = $this->Threads->ThreadNotes->newEntity();
		$this->set('threadNote', $threadNote);

		$threadGroups = $this->Threads->ThreadGroups->find('list')->order('ThreadGroups.name')->all();
		$this->set('threadGroups', $threadGroups);

		$matters = $this->Threads->Matters->find('list')->order('Matters.name')->all();
		$this->set('matters', $matters);

		$oldMatters = $this->Threads->OldMatters->find('list')->order('OldMatters.name')->all();
		$this->set('oldMatters', $oldMatters);
		$this->addToHistory('Thread', $thread->id);
	}

	public function search() {
		$query = $this->Threads->ThreadNotes->find();

		if ($this->request->getData()) {
			$query->where(["note LIKE '%{$this->request->getData('note')}%'"]);
		}
		$query->order(['id' => 'DESC']);
		$query->limit(25);

		$this->set('notes', $query);
	}

}