<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ThreadGroupNotesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('ThreadGroups');
        $this->belongsTo('TeamMembers');
    }
}