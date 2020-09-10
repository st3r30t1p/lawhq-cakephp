<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Lib\AssignRule;

class SystemGeneratedRulesController extends AppController
{
	public function approve($id)
	{
		if (empty($this->request->query('contact_id'))) {
			throw new Exception('Contact ID is required.');
		}

		$systemRule = $this->SystemGeneratedRules->find()
		->contain(['Domains'])
		->where(['SystemGeneratedRules.id' => $id])->first();

		// $findRule = (empty($systemRule->system_generated_rule_id)) ? true : false;
		$rule = $this->SystemGeneratedRules->Rules->findOrCreateSystemRule($this->request->query('contact_id'), false);
		$systemRule->status = 'approved';
		$systemRule->rule_id = $rule->id;

		if ($this->SystemGeneratedRules->save($systemRule)) {
			$rule_set = $this->SystemGeneratedRules->Rules->RuleConditionSets->createSet($rule->id);
			$condition = $this->SystemGeneratedRules->Rules->RuleConditionSets->RuleConditions->newEntity();
			$condition->rule_condition_set_id = $rule_set->id;
			$condition->type = 'domain';
			$condition->search_type = 'contains';
			$condition->search_for = $systemRule->domain->domain;
			if ($this->SystemGeneratedRules->Rules->RuleConditionSets->RuleConditions->save($condition)) {
				$this->redirect(['controller' => 'Rules', 'action' => 'approve', '?' => ['page' => $this->request->query('page')]]);
			}
		}
	}
}