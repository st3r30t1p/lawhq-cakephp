<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RulesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->hasMany('RuleConditions', [
            'conditions' => ['deleted' => 0]
        ]);
        $this->hasMany('RuleConditionSets', [
            'conditions' => ['deleted IS NULL OR deleted = 0']
        ]);
        $this->hasOne('SystemGeneratedRules');
        $this->hasMany('RuleAssignments');
        $this->hasMany('RuleFiles', [
            'conditions' => ['is_deleted IS NULL']
        ]);
        $this->hasOne('Contacts', [
        	'foreignKey' => 'id',
        	'bindingKey' => 'contact_id'
        ]);
    }

    public function validationDefault(Validator $validator) 
    {
    	return $validator
    		->notEmpty('contact_id', 'Please select a contact.');    
    }

    public function findOrCreateSystemRule($contactId, $findRule)
    {
        $rule = null;
        if ($findRule) {
            $rule = $this->find()
            ->where(['contact_id' => $contactId, 'type' => 'system'])->first();
        }

        if (!$rule) {
            $rule = $this->newEntity();
            $rule->contact_id = $contactId;
            $rule->type = 'system';
            $this->save($rule);
        }
        return $rule;
    }
}