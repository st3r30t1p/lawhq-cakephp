<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

class ContactWebsitesTable extends Table {

    public function initialize(array $config) {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts');
    }

    public function beforeMarshal(Event $event, ArrayObject $data)
    {
            $data['website'] = trim($data['website']);
    }

    public function validationDefault(Validator $validator) 
    {
    	return $validator
    		->notEmpty('website', 'This field cannot be left empty');
    }
}