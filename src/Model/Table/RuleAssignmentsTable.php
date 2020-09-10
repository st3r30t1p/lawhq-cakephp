<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RuleAssignmentsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
    	$this->belongsTo('ImportedMessages');
    }

    public function deleteAssignments($rule_condition_set_id)
    {
    	$this->updateAll(
    	   ['deleted' => 1],
    	   ['rule_condition_set_id' => $rule_condition_set_id]
    	);
    }
}