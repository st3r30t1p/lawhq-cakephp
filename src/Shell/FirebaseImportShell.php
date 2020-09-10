<?php
namespace App\Shell;

use App\Lib\DomainInfo;
use App\Lib\GoogleFirebase;
use App\Lib\UrlParts;
use Cake\Console\Shell;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
//use Google\Cloud\Firestore\FirestoreClient;

class FirebaseImportShell extends Shell
{
    public function main()
    {
    	$cmd = 'all';
    	if (!empty($this->args[0])) {
    		$cmd = $this->args[0];
    	}

    	if ($cmd == 'all' || $cmd == 'users') {
    		$this->importUsers();
    	}

    	if (in_array($cmd, ['all', 'messages', 'android', 'ios'])) {
            if ($cmd != 'ios') $this->importMessages();
    		if ($cmd != 'android') $this->importMessagesIos();
    	}

    	if ($cmd == 'all' || $cmd == 'urls') {
    		$this->extractUrls();
    		$this->crawlUrls();
    	}
    }

    public function importUsers() {
    	$this->out('Import Users');

		$firebase = new GoogleFirebase();
		$data = $firebase->list('users');

		$create = 0;
		$update = 0;
		$usersTable = TableRegistry::getTableLocator()->get('ImportedUsers');
		foreach ($data as $userId => $u) {
			try {
				$user = $usersTable->get($userId);
				$update++;
			} catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
				$create++;
				$user = $usersTable->newEntity();
				$user->id = $userId;
				$user->_deleted = false;
				$user->created = new Time($u->createTime);
			}

			$user->phoneNumber = $u->get('phoneNumber') ?? $u->get('phone');
			$user->email = $u->get('email');
			$user->name_firstName = $u->get('name')['firstName'];
			$user->name_lastName = $u->get('name')['lastName'];
			$user->address_address = $u->get('address')['address'];
			$user->address_address2 = $u->get('address')['address2'];
			$user->address_city = $u->get('address')['city'];
			$user->address_state = $u->get('address')['state'];
			$user->address_zip = $u->get('address')['zip'];
			$user->isProfileCompleted = $u->get('isProfileCompleted');
			$user->modified = new Time($u->updateTime);
			$usersTable->save($user);
		}

		$this->out("\tCreated: {$create}");
    	$this->out("\tUpdated: {$update}");
	}

    public function importMessages() {
    	$this->out('Import Messages (Android)');
        $msgTypes = ['sms', 'mms'];

    	$firebase = new GoogleFirebase();
    	$messagesTable = TableRegistry::getTableLocator()->get('ImportedMessages');
        $threadsTable = TableRegistry::getTableLocator()->get('Threads');

    	$create = 0;
    	$update = 0;

    	$msgs = $firebase->list("all_spam_messages");
    	foreach ($msgs as $key => $message) {
            // check msg type
            $type = $message->get('type', 'sms');
            if (!in_array($type, $msgTypes)) {
                $this->out("Unknown type {$type} on {$key}");
                continue;
            }

    		// get or create a thread
            $thread = $threadsTable->findOrCreate([
                'imported_user_id' => $message->get('userId'),
                'from_phone' => $message->get('address'),
                'to_phone' => $message->get('toAddress')
            ]);

            // if the the message alrady exists
    		$msg = $messagesTable->find()->where([
    			'ImportedMessages.thread_id' => $thread->id,
    			'ImportedMessages.msg_id' => $message->get('messageId'),
    			//'ImportedMessages.body' => $message->get('body')
    		])
    		->first();

    		// create it
    		if (!$msg) {
    			$create++;
    			$msg = $messagesTable->newEntity();
                $msg->thread_id = $thread->id;
                $msg->received_time = Time::createFromTimestamp(substr($message->get('time'), 0, 10));
                $msg->direction = $message->get('messageType');
                $msg->msg_id = $message->get('messageId');

                // update thread
                if ($msg->received_time > $thread->last_message_received) {
                    $thread->last_message_received = $msg->received_time;
                }
                $thread->imported_msg_rcvd_count++;
                $threadsTable->save($thread);
    		} else {
                $update++;
            }

            $msg->type = $message->get('type', 'sms');
            if ($msg->type == 'mms') {
                $msg->body = json_encode($message->get('parts', []));
            } else {
                $msg->body = $message->get('body');
            }
            $messagesTable->save($msg);
    	}

    	$this->out("\tCreated: {$create}");
    	$this->out("\tUpdated: {$update}");
    }

    public function importMessagesIos() {
    	$this->out('Import Messages (iOS)');

    	$firebase = new GoogleFirebase();
    	$messagesTable = TableRegistry::getTableLocator()->get('ImportedMessages');
        $threadsTable = TableRegistry::getTableLocator()->get('Threads');

    	$create = 0;
    	$update = 0;

    	$users = $firebase->list("ios_spam");
    	foreach ($users as $userId => $user) {
    		$threads = $user->list('spamMessages');
    		foreach ($threads as $thread) {
    			foreach ($thread->list('messages') as $msgTime => $message) {
                    // get or create a thread
                    $dbThread = $threadsTable->findOrCreate([
                        'imported_user_id' => $userId,
                        'from_phone' => $thread->get('sender'),
                        'to_phone' => $thread->get('receiver')
                    ]);

    				// see if message is already in db
    				$msg = $messagesTable->find()->where([
    					'ImportedMessages.thread_id' => $dbThread->id,
    					'ImportedMessages.msg_id' => $msgTime,
    					'ImportedMessages.body' => $message->get('message')
    				])
    				->first();

    				// create it
    				if (!$msg) {
    					$create++;
    					$msg = $messagesTable->newEntity();
    					$msg->thread_id = $dbThread->id;
    					$msg->received_time = Time::createFromTimestamp(substr($msgTime, 0, 10));
    					$msg->direction = 'received';
    					$msg->msg_id = $msgTime;
    					$msg->body = $message->get('message');
    					$messagesTable->save($msg);

                        $dbThread->imported_msg_rcvd_count++;
    				} else {
                        $update++;
                    }

                    // update thread
                    if ($msg->received_time > $dbThread->last_message_received) {
                        $dbThread->last_message_received = $msg->received_time;
                    }
                    $threadsTable->save($dbThread);
    			}
    		}
    	}

    	$this->out("\tCreated: {$create}");
    	$this->out("\tUpdated: {$update}");
    }

    public function extractUrls() {
    	$this->out('Extract URLs');
    	$messagesTable = TableRegistry::getTableLocator()->get('ImportedMessages');
    	$urlsTable = TableRegistry::getTableLocator()->get('Urls');
    	$msgUrlsTable = TableRegistry::getTableLocator()->get('MessageUrls');

    	$msgs = $messagesTable->find()->order('id');
    	$urlCount = 0;
    	$associationCount = 0;
    	foreach ($msgs as $msg) {
    		$urls = $msg->extractUrls();
    		foreach ($urls as $url) {
    			$urlArray = $url->toArray();
                $urlArray['port IS'] = $urlArray['port'];
                unset($urlArray['port']);
    			$dbUrl = $urlsTable->find()->where($urlArray)->first();

    			if ($dbUrl === null) {
    				$urlCount++;
    				$dbUrl = $urlsTable->newEntity($urlArray);
    				$dbUrl->created = Time::now();
    				$urlsTable->save($dbUrl);
    			}
    			$association = $msgUrlsTable->find()->where(['url_id' => $dbUrl->id, 'imported_message_id' => $msg->id])->first();
    			if ($association === null) {
    				$associationCount++;
    				$association = $msgUrlsTable->newEntity(['url_id' => $dbUrl->id, 'imported_message_id' => $msg->id]);
    				$msgUrlsTable->save($association);
    			}
    		}
    	}

    	$this->out("\tUrls: {$urlCount}");
    	$this->out("\tAssociations: {$associationCount}");
    }

    public function crawlUrls() {
    	$this->out('Crawl URLs');

    	$urlsTable = TableRegistry::getTableLocator()->get('Urls');
    	$urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');

    	$requests = 0;
    	$urls = $urlsTable->find()->order('id');
    	foreach ($urls as $url) {
    		// check if it's already been crawled
            $count = $urlDetailsTable->find()->where(['url_id' => $url->id])->count();
    		if ($count > 0) continue;

            $urlParts = UrlParts::fromArray($url->toArray());
            $urlStr = $urlParts->buildUrl();
    		$this->out("\t{$urlStr}");

    		$requests++;
    		$domainTools = new DomainInfo();
    		$domainTools->crawl($url);
    	}

    	$this->out("\tRequests: {$requests}");
    }
}