<?php
namespace App\Model\Table;

use Cake\ORM\Table;
// use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use ArrayObject;

class RuleConditionSetsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Rules');
        $this->hasMany('RuleConditions', [
            'conditions' => ['deleted' => 0]
        ]);
        $this->hasMany('DomainRuleConditions', [
            'className' => 'rule_conditions',
            'conditions' => ['type' => 'domain', 'deleted' => 0]
        ]);
        $this->hasMany('RuleAssignments', [
            'conditions' => ['deleted' => 0]
        ]);
        // $this->belongsTo('RuleConditions');
    }

    public function beforeMarshal(Event $event, ArrayObject $data)
    {
        if (isset($data['rule_conditions'])) {
            foreach ($data['rule_conditions'] as $condition) {
                $condition['search_for'] = trim($condition['search_for']);
            }
        }
    }

    public function createSet($rule_id)
    {
        $set = $this->newEntity();
        $set->rule_id = $rule_id;
        $this->save($set);
        return $set;
    }

    public function deleteSetsByRuleId($id)
    {
        $ruleSets = $this->find()
        ->where(['rule_id' => $id]);
        foreach ($ruleSets as $set) {
            $set->deleted = 1;
            if ($this->save($set)) {
                $this->RuleConditions->deleteConditionsBySetId($set->id);
                $this->RuleAssignments->deleteAssignments($set->id);
            }
        }
    }
}