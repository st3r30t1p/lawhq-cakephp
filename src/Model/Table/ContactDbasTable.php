<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

class ContactDbasTable extends Table {

    public function initialize(array $config) {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts', ['foreignKey' => 'contact_id']);
    }

    public function beforeMarshal(Event $event, ArrayObject $data)
    {
            $data['name'] = trim($data['name']);
    }

    public function validationDefault(Validator $validator) 
    {
    	return $validator
    		->notEmpty('name', 'This field cannot be left empty');
    }
}