<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;

class ContactsTable extends Table {
	
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');

        $this->hasMany('contactNotes', [
            'sort' => ['contactNotes.created' => 'ASC'],
            'conditions' =>['contactNotes.is_deleted IS NULL']
        ]);

        $this->hasMany('contactDbas', [
            'sort' => ['contactDbas.name' => 'ASC'],
            'conditions' => ['contactDbas.is_deleted IS NULL']
        ]);
        
        $this->hasMany('contactEmails', [
            'sort' => ['contactEmails.is_primary' => 'DESC'],
            'conditions' => ['contactEmails.is_deleted IS NULL']
        ]);

        $this->hasMany('contactWebsites', [
            'sort' => ['contactWebsites.is_primary' => 'DESC'],
            'conditions' => ['contactWebsites.is_deleted IS NULL']
        ]);

        $this->hasMany('contactPhoneNumbers', [
            'sort' => ['contactPhoneNumbers.is_primary' => 'DESC'],
            'conditions' => ['contactPhoneNumbers.is_deleted IS NULL']
        ]);

        $this->hasMany('contactAddresses', [
            'sort' => ['contactAddresses.is_primary' => 'DESC'],
            'conditions' => ['contactAddresses.is_deleted IS NULL']
        ]);

        $this->hasOne('primaryEmails', [
            'className' => 'contactEmails',
            'conditions' => [
                'primaryEmails.is_primary' => 1, 
                'primaryEmails.is_deleted IS NULL'
            ]
        ]);

        $this->hasOne('primaryPhones', [
            'className' => 'contactPhoneNumbers',
            'conditions' => [
                'primaryPhones.is_primary' => 1, 
                'primaryPhones.is_deleted IS NULL'
            ]
        ]);

        $this->hasOne('primaryAddresses', [
            'className' => 'contactAddresses',
            'conditions' => [
                'primaryAddresses.is_primary' => 1, 
                'primaryAddresses.is_deleted IS NULL'
            ]
        ]);

        $this->hasMany('contactRelationships', [
            'conditions' => ['contactRelationships.is_deleted IS NULL']
        ]);

        $this->hasMany('targetRelationships', [
            'className' => 'contactRelationships', 
            'foreignKey' => 'contact_id_target',
            'sort' => ['targetRelationships.relationship' => 'ASC'],
            'conditions' => ['targetRelationships.is_deleted IS NULL']
        ]);

        $this->hasOne('States', ['bindingKey' => 'company_incorporated_in', 'foreignKey' => 'code']);

        $this->belongsTo('Rules');
    }

    public function validationDefault(Validator $validator) 
    {
        $validator->notEmpty('person_first_name', 'Please enter a first name.', function($context) {
            return $context['data'] ? $context['data']['type'] == 'person' : false;
        })
        ->notEmpty('person_last_name', 'Please enter a last name.', function($context) {
            return $context['data'] ? $context['data']['type'] == 'person' : false;
        })
        ->notEmpty('company_name', 'Please enter a company name.', function($context) {
           return $context['data'] ? $context['data']['type'] == 'company' : false;
        });

    	return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add(function ($entity, $options) use($rules) {
            if ($entity->type == 'company') {
                $rule = $rules->isUnique(['company_registration_number', 'company_incorporated_in'], 'There is already a contact that has this company number in this state.');
                return $rule($entity, $options);
            }

            return true;
        });

        return $rules;
    }

    public function getContactName($id)
    {
        $contact = $this->get($id);
        return $contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn();
    }

    public function list()
    {
        return $this->find('list', [
            'keyField' => 'id',
            'valueField' => 'nameWithState',
        ])
        ->where(['is_deleted IS NULL', 'company_domestic_foreign = "domestic" OR company_domestic_foreign IS NULL'])
        ->order(['person_first_name' => 'ASC', 'company_name' => 'ASC'])
        ->all();
    }

    public function allList()
    {
        return $this->find('list', [
            'keyField' => 'id',
            'valueField' => 'nameWithState',
        ])
        ->where(['is_deleted IS NULL'])
        ->order(['person_first_name' => 'ASC', 'company_name' => 'ASC'])
        ->all();
    }
}