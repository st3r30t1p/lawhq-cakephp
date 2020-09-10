<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CourtsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->belongsTo('MatterCourts');
    	$this->addBehavior('Timestamp');
    }
}