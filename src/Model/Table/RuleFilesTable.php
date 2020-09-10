<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RuleFilesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Files');
    }

}