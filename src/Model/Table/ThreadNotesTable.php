<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ThreadNotesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Threads');
        $this->belongsTo('TeamMembers');
    }
}