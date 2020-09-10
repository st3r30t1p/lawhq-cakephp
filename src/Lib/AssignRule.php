<?php
namespace App\Lib;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;

Class AssignRule
{
	function __construct($rule)
	{
		$this->ruleId = $rule->id;
		$this->contactId = $rule->contact_id;
		$this->ruleConditionSets = $rule->rule_condition_sets;
		$this->ruleAssignments = [];
		$this->assignRules();
	}

	public function assignRules()
	{
		foreach ($this->ruleConditionSets as $keySet => $set) {
			$tableInfo = $set->tableInfo();
			$table = TableRegistry::getTableLocator()->get($tableInfo['table']);
			$urls = $table->find();
			$urls->join($tableInfo['joins']);
			
			if ($tableInfo['table'] == 'UrlDetails') {
				$urls->select(['url_id', 'mu.imported_message_id', 'im.thread_id', 'thread.from_phone']);
			}

			foreach ($set->rule_conditions as $key => $condition) {
				if (empty($condition->type) || empty($condition->search_for)) { continue; }
				// When searching WHOIS for a registrant name it needs to be all uppercase, else if an email, all lower
				$searchFor = ($condition->type == 'reg_name') ? trim(strtoupper($condition->search_for)) : trim(strtolower($condition->search_for));
				// When searching JSON for an ip address it needs to be in brackets, anything else just needs to be in quotes
				$this->searchFor = ($condition->type == 'ips') ? '["'.$searchFor.'"]' : '"'.$searchFor.'"';
				$order = ($key == 0) ? 'where' : 'andWhere';
				if ($condition->type == 'domain') {
					$urls->$order(['UrlDetails.url LIKE' => "%{$searchFor}%"]);
				}
				if ($condition->type == 'phone_number') {
					$urls->$order(['thread.from_phone' => "{$searchFor}"]);
				}
				if ($condition->type == 'message_text') {
					$urls->$order(['ImportedMessages.body LIKE' => "%{$searchFor}%"]);
				}

				// Domain details table, registrant json info search
				if (in_array($condition->type, ['ips', 'reg_email', 'reg_name'])) {
					$urls->$order(function (QueryExpression $exp, Query $q) {
				        $jsonExtract = $q->func()->json_extract([
				        	'info' => 'identifier', '$.data.ips',
				            'info' => 'identifier', '$.data.whois.reg_name',
				            'info' => 'identifier', '$.data.whois.tech_name',
				            'info' => 'identifier', '$.data.whois.admin_name',
				            'info' => 'identifier', '$.data.whois.reg_email',
				            'info' => 'identifier', '$.data.whois.tech_email',
				            'info' => 'identifier', '$.data.whois.admin_email',
				        ]);
				        $jsonContains = $q->func()->json_contains([
				        	$jsonExtract, $this->searchFor
				        ]);
				        return $exp->eq($jsonContains, true);
				    });
				}
			}

			if ($tableInfo['table'] == 'UrlDetails') {
				$urls->group(['UrlDetails.url_id', 'mu.imported_message_id']);
			}
			foreach ($urls as $key => $url) {
				// if (empty($url->im['thread_id']) || empty($url->mu['imported_message_id'])) { continue; }
				if ($tableInfo['table'] == 'ImportedMessages') {
					$assign = "({$this->ruleId}, {$this->contactId}, {$set->id}, {$url['thread_id']}, {$url['id']}, 0, NOW(), NOW())";
				} else {
					$assign = "({$this->ruleId}, {$this->contactId}, {$set->id}, {$url->im['thread_id']}, {$url->mu['imported_message_id']}, 0, NOW(), NOW())";
				}
				array_push($this->ruleAssignments, $assign);
			}
			
			$this->saveRules();
		}
	}

	public function saveRules()
	{
		if (empty($this->ruleAssignments)) { return; }
		$connection = ConnectionManager::get('default');
		$query = 'INSERT INTO rule_assignments (rule_id, contact_id, rule_condition_set_id, thread_id, imported_message_id, deleted, created, modified) VALUES ' . implode(', ', $this->ruleAssignments) . ' ON DUPLICATE KEY UPDATE deleted=VALUES(deleted)';
		$results = $connection->execute($query);
	}
}
