<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\ORM\TableRegistry;
use App\Lib\AssignRule;
use Cake\Datasource\ConnectionManager;

class RulesCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
    	$rulesTable = TableRegistry::getTableLocator()->get('Rules');
    	$rules = $rulesTable->find()
        ->contain(['RuleConditionSets', 'RuleConditionSets.RuleConditions'])
        ->where(['ignore_rule' => 0]);

    	foreach ($rules as $rule) {
            // Set all rule assignments to deleted. Any rules that are still current will be updated to deleted = 0
            $this->setRuleAssingmnetsToDeleted($rule->id);
            if (empty($rule->rule_condition_sets)) { continue; }
            $assignRule = new AssignRule($rule);
    	}
    }

    public function setRuleAssingmnetsToDeleted($ruleId)
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute("UPDATE rule_assignments SET deleted = 1 WHERE rule_id = {$ruleId}");
    }
}