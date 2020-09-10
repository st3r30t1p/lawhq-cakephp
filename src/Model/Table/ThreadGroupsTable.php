<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ThreadGroupsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->hasMany('Threads', ['sort' => 'Threads.last_message_received DESC']);
        $this->hasMany('ThreadGroupNotes', ['sort' => ['ThreadGroupNotes.created' => 'ASC']])->setConditions(['ThreadGroupNotes.is_deleted IS NULL']);;
    }
}