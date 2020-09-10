<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MessageUrlsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('ImportedMessages');
    }
}