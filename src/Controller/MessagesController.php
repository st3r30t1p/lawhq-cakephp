<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class MessagesController extends AppController {
	public $paginate = [
	    'limit' => 200
	];

	public function index() {
		$where = ['direction' => 'received'];
		$filter = [];
		$this->set('showDirection', false);

		if ($this->request->getQuery('domain')) {
			$domain = $host = $this->request->getQuery('domain');
			$tld = '';
			if ($this->request->getQuery('tld')) {
				$tld = $this->request->getQuery('tld');
				$host .= '.' . $tld;
			} else {
				$parts = explode('.', $host);
				$domain = $parts[0];
				$tld = $parts[1];
			}
			$filter['Domain'] = $host;

			$urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');
			$msgUrlsTable = TableRegistry::getTableLocator()->get('MessageUrls');
			$urlDetails = $urlDetailsTable->find()->select(['url_id'])->where(['domain' => $domain, 'tld' => $tld]);
			$urlIds = [];
			foreach ($urlDetails as $urlDetail) {
				$urlIds[] = $urlDetail['url_id'];
			}
			$urlIds = array_unique($urlIds);

			$msgIds = [];
			$msgUrls = $msgUrlsTable->find()->select(['imported_message_id'])->where(['url_id IN' => $urlIds]);
			foreach ($msgUrls as $msgUrl) {
				$msgIds[] = $msgUrl['imported_message_id'];
			}
			$where['ImportedMessages.id IN'] = $msgIds;
		}
		if ($this->request->getQuery('from')) {
			unset($where['direction']);
			$where['Threads.from_phone'] = $this->request->getQuery('from');
			$filter['From'] = $this->request->getQuery('from');
			$this->set('showDirection', true);
		}
		if ($this->request->getQuery('to')) {
			unset($where['direction']);
			$where['Threads.to_phone'] = $this->request->getQuery('to');
			$filter['To'] = $this->request->getQuery('to');
			$this->set('showDirection', true);
		}

		$this->set('filter', $filter);

		$messagesTable = TableRegistry::getTableLocator()->get('ImportedMessages');
		$messages = $messagesTable->find()
		->where($where)
		->order('received_time DESC')
		->contain(['Threads', 'Threads.ImportedUsers', 'RuleAssignments'])
		->limit(1000);

		$this->set('messages', $this->paginate($messages));
	}

}