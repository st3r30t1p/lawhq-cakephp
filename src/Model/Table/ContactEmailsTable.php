<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

class ContactEmailsTable extends Table {

    public function initialize(array $config) {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts');
    }

    public function beforeMarshal(Event $event, ArrayObject $data)
    {
            $data['email'] = trim($data['email']);
    }

    public function validationDefault(Validator $validator) 
    {
    	return $validator
    		->requirePresence('email')
    		->add('email', 'validFormat', [
    		    'rule' => 'email',
    		    'message' => 'E-mail must be valid'
    		]);
    }
}