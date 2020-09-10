<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ImportedMessagesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Threads');
        $this->hasOne('RuleAssignments');
    }
}