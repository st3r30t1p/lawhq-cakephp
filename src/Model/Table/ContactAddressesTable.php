<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ContactAddressesTable extends Table {

    public function initialize(array $config) {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts', ['foreignKey' => 'contact_id']);
        $this->hasOne('Countries', ['propertyName' => 'country']);
        $this->hasOne('States', ['propertyName' => 'state', 'bindingKey' => 'state', 'foreignKey' => 'code']);
    }

    public function validationDefault(Validator $validator) 
    {
    	return $validator
    		->notEmpty('address_1', 'Please fill this field.');
    }
}