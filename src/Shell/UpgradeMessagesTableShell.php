<?php
namespace App\Shell;

use App\Lib\DomainInfo;
use App\Lib\GoogleFirebase;
use App\Lib\UrlParts;
use Cake\Console\Shell;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
//use Google\Cloud\Firestore\FirestoreClient;

class UpgradeMessagesTableShell extends Shell
{
    public function main()
    {
		$messagesTable = TableRegistry::getTableLocator()->get('ImportedMessages');
        $threadsTable = TableRegistry::getTableLocator()->get('Threads');
        $messages = $messagesTable->find()->where(['thread_id IS' => NULL]);
        foreach ($messages as $message) {
            $thread = $threadsTable->find()
            ->where(['imported_user_id' => $message->imported_user_id, 'from_phone' => $message->from_phone, 'to_phone' => $message->to_phone])
            ->first();
            if ($thread) {
                if ($message->direction == 'received') {
                    $thread->imported_msg_rcvd_count++;
                    if ($message->received_time > $thread->last_message_received) {
                        $thread->last_message_received = $message->received_time;
                    }
                }
            } else {
                $thread = $threadsTable->newEntity();
                $thread->imported_user_id = $message->imported_user_id;
                $thread->from_phone = $message->from_phone;
                $thread->to_phone = $message->to_phone;
                $thread->imported_msg_rcvd_count = 1;
                $thread->last_message_received = $message->received_time;
            }
            $threadsTable->save($thread);
            $message->thread_id = $thread->id;
            $messagesTable->save($message);
            $this->out("Msg {$message->id}");
        }
    }
}