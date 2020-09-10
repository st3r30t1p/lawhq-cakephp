<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MatterContactsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts');
        $this->belongsTo('TeamMembers');
        $this->belongsTo('ImportedUsers');
    }
}