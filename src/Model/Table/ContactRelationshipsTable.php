<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\Event\Event;
use ArrayObject;
use Cake\ORM\Entity;

class ContactRelationshipsTable extends Table {

    public function initialize(array $config) 
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts');
    }

    public function validationDefault(Validator $validator) 
    {
        // Make sure the fields are not empty
        $validator->notEmpty('contact_id', 'Select a contact.');
        $validator->notEmpty('contact_id_target', 'Select a contact.');
        $validator->notEmpty('relationship', 'Select relationship.');
        $validator
            ->add('relationship', [
                'duplicateCheck' => [
                    'rule' => [$this, 'duplicateCheck'],
                    'message' => 'This relationship already exists.',
                    'on' => 'create'
                ],
                // Check if this contact already has a registed agent
                'registeredAgentCheck' => [
                    'rule' => [$this, 'registeredAgentCheck'],
                    'message' => 'This contact already has a registed agent.',
                    'on' => 'create'
                ],
                // Subsidiary - both contacts must be domestic entity
                'subsidiaryCheck' => [
                    'rule' => [$this, 'subsidiaryCheck'],
                    'message' => 'Both contacts must be domestic for subsidiary relationship.',
                    'on' => function ($context) {
                        return $context['data']['relationship'] == 'subsidiary';
                    }
                ],
                // Stockholder - ContactIDTarget can only be domestic company
                'stockholderCheck' => [
                    'rule' => [$this, 'stockholderCheck'],
                    'message' => 'You can only be a stockholder in a domestic entity.',
                    'on' => function ($context) {
                        return $context['data']['relationship'] == 'stockholder';
                    }
                ]
            ]
        );
        $validator
            ->add('contact_id_target', [
                // Make sure a relationship is not the same contact on both sides
                'comparison' => [
                    'rule' => function ($value, $context) {
                        if (isset($context['data']['contact_id'])) {
                            return intval($value) != intval($context['data']['contact_id']) ;
                        }
                        return true;
                    },
                    'message' => 'Selected contact cannot be the same as current contact.'
                ],
                // Foreign Entity can only have a Registered Agent and Foreign Entity relationship.
                'foreignEntityCheck' => [
                    'rule' => [$this, 'foreignEntityCheck'],
                    'message' => 'Foreign entities can have one registered agent and one foreign entity.',
                    'on' => 'create'
                ]
            ]   
        );


    	return $validator;
    }

    public function duplicateCheck($value, $context)
    {
        $exists = $this->exists(['contact_id' => $context['data']['contact_id'], 'relationship' => $context['data']['relationship'], 'contact_id_target' => $context['data']['contact_id_target']]);
        if ($exists) {
            // return false since it does not pass validation
            return false;
        }
        // return true since it does pass validation
        return true;
    }

    public function registeredAgentCheck($value, $context)
    {
        if ($context['data']['relationship'] == 'registered_agent') {
            $exists = $this->exists(['relationship' => 'registered_agent', 'contact_id_target' => $context['data']['contact_id_target'], 'is_deleted IS NULL']);
            if ($exists) {
                return false;
            }
        }
        return true;
    }

    public function foreignEntityCheck($value, $context)
    {
        if ($context['data']['relationship'] != 'foreign_entity') { return true; }

        if (!in_array($context['data']['relationship'], ['registered_agent', 'foreign_entity'])) {
            return false;
        } else if ($context['data']['relationship'] == 'foreign_entity') {
            $exists = $this->exists(['relationship' => 'foreign_entity', 'contact_id' => $context['data']['contact_id'], 'is_deleted IS NULL']);
            if ($exists) {
                return false;
            }
        }
        return true;
    }

    public function subsidiaryCheck($value, $context)
    {
        $currentContactEntityType = $context['providers']['passed']['contact_entity_type'];
        $targetContactId = ($context['data']['side'] == 'contactRelationships') ? $context['data']['contact_id_target'] : $context['data']['contact_id'];
        $targetContact = $this->Contacts->get($targetContactId);

        if ($currentContactEntityType != 'domestic' || $targetContact->company_domestic_foreign != 'domestic') {
            return false;
        }
        return true;
    }

    public function stockholderCheck($value, $context)
    {
        $targetContact = $this->Contacts->get($context['data']['contact_id_target']);
        if ($targetContact->company_domestic_foreign != 'domestic') {
            return false;
        }
        return true;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add(function ($entity, $options) use($rules) {
            $rule = $rules->isUnique(['contact_id_target', 'relationship', 'contact_id'], 'This relationship already exists.');
            return $rule($entity, $options);
        });

        return $rules;
    }
}