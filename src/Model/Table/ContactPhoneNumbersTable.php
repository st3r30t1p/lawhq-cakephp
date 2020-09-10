<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

class ContactPhoneNumbersTable extends Table {

    public function initialize(array $config) {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts', ['foreignKey' => 'contact_id']);
    }

    public function beforeMarshal(Event $event, ArrayObject $data)
    {
            $data['phone_number'] = trim($data['phone_number']);
    }

    public function validationDefault(Validator $validator) 
    {
    	return $validator
    		->add('phone_number', 'validFormat',[
    		        'rule' => array('custom', '/^([0-9]*-[0-9]*)+$/'),
    		        'message' => 'Please include dashes.'
    		]);
    }
}