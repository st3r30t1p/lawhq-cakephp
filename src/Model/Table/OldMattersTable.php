<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class OldMattersTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->hasMany('Threads', ['sort' => 'Threads.last_message_received DESC']);
    }
}