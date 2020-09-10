<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ThreadsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->hasMany('ImportedMessages', ['sort' => 'ImportedMessages.received_time']);
        $this->hasMany('ThreadNotes', ['sort' => ['ThreadNotes.created' => 'ASC']])->setConditions(['ThreadNotes.is_deleted IS NULL']);
        $this->belongsTo('ImportedUsers');
        $this->belongsTo('ThreadGroups');
        $this->belongsTo('Matters');
        $this->hasMany('RuleAssignments');

        $this->belongsTo('OldMatters');
    }

    public function findOrCreate($search, callable $callback = null, $options = []) {
        return parent::findOrCreate($search, function($entity) {
            $entity->imported_msg_rcvd_count = 0;
            $entity->last_message_received = '1970-01-01 00:00:01';
        });
    }
}