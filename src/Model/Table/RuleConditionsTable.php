<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class RuleConditionsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('RuleConditionSets');
        $this->belongsTo('Rules');
    }

    public function validationDefault(Validator $validator) 
    {
    	$validator->notEmpty('search_for', 'Please enter a value.');
        return $validator;
    }

    public function countRemainingConditions($id = null)
    {
        $conditions = $this->find()
        ->where(['rule_condition_set_id' => $id, 'deleted IS NULL']);
        return $conditions->count();
    }

    public function deleteConditionsBySetId($id)
    {
        $this->updateAll(
           ['deleted' => 1],
           ['rule_condition_set_id' => $id]
        );
    }
}