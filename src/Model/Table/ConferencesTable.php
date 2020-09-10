<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;

class ConferencesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
    	$this->belongsTo('TeamMembers');
	}

	public function validationDefault(Validator $validator) 
	{
        $validator
            ->notEmpty('meeting_number');

	    return $validator;
	}
}