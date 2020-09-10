<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class SystemRules {
	function __construct($depth)
	{
		$this->rulesTable = TableRegistry::getTableLocator()->get('Rules');
		$this->systemRulesTable = TableRegistry::getTableLocator()->get('SystemGeneratedRules');
		$this->depth = $depth;
		$this->table_column = ($this->depth == 1) ? 'rule_condition_set_id' : 'system_generated_rule_id';

		if ($depth == 1) {
			$this->findDomainRuleConditions();
		} else {
			$this->systemGeneratedRules();
		}
	}

	public function findDomainRuleConditions() 
	{   
	    // Find each rule_condition_set where ALL of the underlying conditions is/are type=domain
	    $rules = $this->rulesTable->find()
	    ->contain(['RuleConditionSets', 'RuleConditionSets.DomainRuleConditions'])
	    ->where(['type' => 'user']);

	    foreach ($rules as $rule) {
	        if (empty($rule->rule_condition_sets)) {
	            continue;
	        }
	        $this->details = new UrlDetils($rule);
	        if (!empty($this->details->redirects)) {
	        	$this->prepareDomainRulesToSave();
	        } 
	    }
	}

	public function systemGeneratedRules()
	{
		$rules = $this->systemRulesTable->find()
		->contain(['Domains', 'Rules'])
		->where(['Rules.ignore_rule' => 0, 'status' => 'approved']);

		foreach ($rules as $rule) {
			$this->details = new UrlDetils($rule);
			if (!empty($this->details->redirects)) {
				$this->prepareDomainRulesToSave();
			} 
		}
	}

	public function prepareDomainRulesToSave()
	{
		$system_rules = [];
		foreach ($this->details->redirects as $domain) {
			if (!$domain->skip && !$this->systemRulesTable->exists(['domain_id' => $domain->domain['id']]) &&
				$domain->domain['id'] != $domain->redirects_with_domain_id
			) {
				// $system_rule = $this->createFindRule($this->details->rule);
		    	$generated = "({$domain->rule_set_id}, {$domain->domain['id']}, {$domain->redirects_with_domain_id}, 'domain_redirect', NOW(), NOW())";
		    	array_push($system_rules, $generated);
			}
		}
		$this->saveSystemRules($system_rules);
	}

	public function saveSystemRules($system_rules)
	{
	    if (empty($system_rules)) { return; }
	    $connection = ConnectionManager::get('default');
	    $query = "INSERT IGNORE INTO system_generated_rules ({$this->table_column}, domain_id, redirects_with_domain_id, type, created, modified) VALUES " . implode(', ', $system_rules);
	    $results = $connection->execute($query);
	}

	public function createFindRule($basedOffRule)
	{
		$contact_id = ($this->depth == 1) ? $basedOffRule->contact_id : $basedOffRule->rule->contact_id;
	    // $rule = $this->rulesTable->find()
	    // ->where(['contact_id' => $contact_id, 'type' => 'system'])->first();
	    $rule = null;

	    if (!$rule) {
	        $rule = $this->rulesTable->newEntity();
	        $rule->contact_id = $contact_id;
	        $rule->type = 'system';
	        $rule->status = 'pending';
	        $this->rulesTable->save($rule);
	    }

	    return $rule;
	}
}

class UrlDetils {
	function __construct($rule)
	{
		$this->rule = $rule;
		$this->urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');
		$this->loopOverDomainRuleSets();
	}

	public function loopOverDomainRuleSets()
	{
		// If rule condition sets are present we need to loop over those to check all sets agsinst the url_details
		// Else if creating rules from rules then loop over those
		if (!empty($this->rule->rule_condition_sets)) {
			foreach ($this->rule->rule_condition_sets as $rule_set) {
				if (empty($rule_set->domain_rule_conditions)) {
					continue;
				}
				$this->searchUrlDetails($rule_set->domain_rule_conditions, $rule_set->id);
			}
		} else {
			$this->searchUrlDetails([['search_for' => $this->rule->domain->domain]], $this->rule->id);
		}
	}

	public function searchUrlDetails($search_for, $rule_set_id = null)
	{
		$ruleConditionsTable = TableRegistry::getTableLocator()->get('RuleConditions');
		// Get url_id(s) to search for all related domains
		$url_ids = $this->getUrlIds($search_for);
		// debug($url_ids->toArray());
		if (empty($url_ids)) { return; }
		$ids = [];
		foreach ($url_ids as $url) {
		    array_push($ids, $url->url_id);
		}
		$ids = '("' . implode('", "', $ids) . '")';
		// Search url_details for for any matching url_id. This will give us all redirects.
		$redirects = $this->urlDetailsTable->find()
		->join([
		    'table' => 'domains',
		    'alias' => 'domain',
		    'type' => 'INNER',
		    'conditions' => 'domain.domain = CONCAT(UrlDetails.domain, ".", UrlDetails.tld)',
		])
		->select(['domain', 'domain.id', 'domain.ignore_on_system_generated_rules', 'domain.domain'])
		->where(["url_id IN {$ids}", 'domain.ignore_on_system_generated_rules' => 0])
		->group(['UrlDetails.domain', 'UrlDetails.tld']);

		foreach ($redirects as $key => $redirect) {
			$redirect->skip = false;
			if ($ruleConditionsTable->exists(['search_for' => $redirect->domain['domain'], 'deleted' => 0])) {
				$redirect->skip = true;
			} 			
			$redirect->rule_set_id = $rule_set_id;
			$redirect->redirects_with_domain_id = $url_ids->first()->domain['id'];
		
		}
		$this->redirects = $redirects;
	}

	public function getUrlIds($search_for)
	{
		// Search url_details where url LIKE conditions, get url_id(s) to then get all related domains
		$url_details_ids = $this->urlDetailsTable->find()
		->join([
		    'table' => 'domains',
		    'alias' => 'domain',
		    'type' => 'INNER',
		    'conditions' => 'domain.domain = CONCAT(UrlDetails.domain, ".", UrlDetails.tld)',
		])
		->select(['url_id', 'domain.id', 'domain.domain'])
		->distinct(['url_id']);

		foreach ($search_for as $key => $condition) {
		    if ($key == 0) {
		       $url_details_ids->where(["url LIKE '%{$condition['search_for']}%'"]);
		    } else {
		        $url_details_ids->andWhere(["url LIKE '%{$condition['search_for']}%'"]);
		    } 
		}

		return $url_details_ids;
	}
}