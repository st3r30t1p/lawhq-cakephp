<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

class TeamMemberLicensesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
    	$this->belongsTo('TeamMembers');
        $this->belongsTo('States');
	}

	public function validationDefault(Validator $validator) 
	{
        $validator
            ->notEmpty('state_id')
            ->notEmpty('number');

	    return $validator;
	}

    public function beforeMarshal(Event $event, ArrayObject $data)
    {
        $data['number'] = trim($data['number']);
    }
}