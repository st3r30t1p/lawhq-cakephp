<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class Rule extends Entity
{
	public function ruleAppliedCount()
	{
		// Get count of times rule has been applied
		$ruleAssignmentsTable = TableRegistry::getTableLocator()->get('RuleAssignments');
		$total = $ruleAssignmentsTable->find();
		$total->select(['count' => $total->func()->count('*')])
		->where(['rule_id' => $this->id, 'deleted' => 0]);
		return $total->first()->count;
	}

	public function ruleConflictsCount()
	{
		// Get conflicts count
		$ruleAssignmentsTable = TableRegistry::getTableLocator()->get('RuleAssignments');
		$rule_threads = $ruleAssignmentsTable->find()
		->select(['imported_message_id'])->where(['rule_id' => $this->id, 'deleted' => 0]);

		$conflicts = $ruleAssignmentsTable->find()
		->select(['imported_message_id'])
		->where(['imported_message_id IN' => $rule_threads, 'contact_id !=' => $this->contact_id])
		->group(['imported_message_id']);

		return $conflicts->count();
	}
}