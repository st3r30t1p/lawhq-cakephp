<?php
namespace App\Controller;

use App\Controller\AppController;

class RuleConditionsController extends AppController
{
	public function delete($id = null)
	{
	    $condition = $this->RuleConditions->find()
	    ->where(['RuleConditions.id' => $id])
	    ->contain(['RuleConditionSets'])->first();
	    $condition->deleted = 1;

	    if ($this->RuleConditions->save($condition)) {
	        $this->Flash->success(__('The rule has been deleted.'));
	    } else {
	        $this->Flash->error(__('There has been a problem deleting the rule.'));
	    }
	    // Set all rule assingments in the rule condition rule set to deleted as the rule set has now been modified
	    $this->RuleConditions->RuleConditionSets->RuleAssignments->deleteAssignments($condition->rule_condition_set_id);
	    // If all conditions are deleted in a rule set, delete that rule set
	    if ($this->RuleConditions->countRemainingConditions($condition->rule_condition_set_id) == 0) {
	    	$condition->rule_condition_set->deleted = 1;
	    	$this->RuleConditions->RuleConditionSets->save($condition->rule_condition_set);
	    }

	    return $this->redirect(['controller' => 'Rules', 'action' => 'edit', $condition->rule_condition_set->rule_id]);
	}
}