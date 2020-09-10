<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MatterNotesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('TeamMembers');
        $this->belongsTo('Matters');
    }
}