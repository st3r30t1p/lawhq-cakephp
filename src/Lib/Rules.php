<?php
namespace App\Lib;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class Rules {
	function __construct($rule)
	{
		$this->rule = $rule;
		$this->depth = 1;
		$this->systemReason = '';
		// This is only used on the rules approve page since those rules pull from the system_generated_rules table
		if (isset($this->rule->system_generated_rule_id)) {
			$this->getDepth($this->rule->system_generated_rule_id);
		}
		// If there are system generated rules then we need to create a reason
		if (isset($this->rule->system_generated_rule)) {
			$this->generateReason();
		}
	}

	public function generateReason()
	{
		if (isset($this->rule->system_generated_rule->rule_condition_set_id) || empty($this->rule->system_generated_rule->system_generated_rule_id)) {
			$this->firstLevelReason();
			// Also find all other user doamins that this domain redirects with
			$this->findUserRulesThatDomainRedirectsWith();
		} else {
			// If depth is >= 2 we want to reason to be recursive and keep going down until reaching the rule it was based off of
			$this->recursiveReason($this->rule->system_generated_rule);
		}
	}

	public function firstLevelReason()
	{	
		$domainRelationships = TableRegistry::getTableLocator()->get('DomainRelationships');
		$relationship = $domainRelationships->getRelationship($this->rule->system_generated_rule->domain_id, $this->rule->system_generated_rule->redirects_with_domain_id);
		$times = ($relationship->count == 1) ? 'time' : 'times';
		$redirects = '';

		$systemRuleConditions = $this->loadRuleConditionsBySetId($this->rule->system_generated_rule->rule_condition_set_id);
		foreach ($systemRuleConditions as $key => $condition) {
			if ($condition->type != 'domain') {
				continue;
			}
			if ($systemRuleConditions->count() == 1) {
				$redirects .= "with {$condition->search_for}";
			} else {				
				if ($key == 0) {
					$redirects .= "where domain contains {$condition->search_for}";
				} else {
					$redirects .= " and {$condition->search_for}";
				}
			}
		}
		// $this->rule->system_generated_rule->rule_condition_set->rule_id
		$messages = Router::url(['controller' => 'DomainRelationships', 'action' => 'messages', $relationship->id]);
		$rule = Router::url(['controller' => 'Rules', 'action' => 'edit', 'id' => $this->rule->system_generated_rule->rule_condition_set->rule_id]);
		$domain = Router::url(['controller' => 'Domains', 'action' => 'relationships', $this->rule->system_generated_rule->domain->domain]);
		$this->systemReason .= "<div><a href='{$domain}'>{$this->rule->system_generated_rule->domain->domain}</a> has been part of redirects {$redirects} <b><a href='{$messages}'>{$relationship->count}</b></a> {$times} <a href='{$rule}'>(rule {$this->rule->system_generated_rule->rule_condition_set->rule_id}).</a></div>";
	}

	public function findUserRulesThatDomainRedirectsWith()
	{
		$domain =  $this->rule->system_generated_rule->domain->domain;
		$domainId = $this->rule->system_generated_rule->domain_id;
		$redirectDomainId = $this->rule->system_generated_rule->redirects_with_domain_id;

		$connection = ConnectionManager::get('default');
		$query = "SELECT rcs.`rule_id`, rc.`search_for`, d.`id` FROM domains d 
				JOIN rule_conditions AS rc ON rc.`search_for` = d.`domain`
				JOIN rule_condition_sets rcs ON rcs.`id` = rc.`rule_condition_set_id`
				JOIN rules on rules.`id` = rcs.`rule_id`
				WHERE d.`id` IN (
					SELECT domain_id FROM domain_relationships dr
					WHERE (dr.`domain_id` = {$domainId} OR domain_id_link = {$domainId})
					UNION
					SELECT domain_id_link FROM domain_relationships dr
					WHERE (dr.`domain_id` = {$domainId} OR domain_id_link = {$domainId})
		) AND rc.`type` = 'domain' AND d.`id` NOT IN ({$domainId}, {$redirectDomainId}) AND rc.`deleted` = 0 AND rules.`type` = 'user'";

		 $redirects = $connection->execute($query)->fetchAll('assoc');

		 foreach ($redirects as $redirect) {
		 	$this->singleConditionReason($domainId, $redirect['id'], $redirect['rule_id'], $domain, $redirect['search_for']);
		 }
	}

	public function loadRuleConditionsBySetId($id)
	{
		$ruleConditionsTable = TableRegistry::getTableLocator()->get('RuleConditions');
		$conditions = $ruleConditionsTable->find()
		->where(['rule_condition_set_id' => $id]);
		return $conditions;
	}

	public function getDepth($system_generated_rule_id)
	{
		$systemGeneratedRulesTable = TableRegistry::getTableLocator()->get('SystemGeneratedRules');
		$systemRule = $systemGeneratedRulesTable->get($system_generated_rule_id);

		if (empty($systemRule->system_generated_rule_id)) {
			$this->depth++;
			return;
		} else {
			$this->depth++;
			$this->getDepth($systemRule->system_generated_rule_id);
		}
	}

	public function singleConditionReason($domainId = null, $redirectId = null, $ruleId = null, $mainDomain = null, $redirectDomain = null)
	{
		// $domainId = (isset($domainId)) ? $domainId : $this->domain_id;
		// $redirectId = (isset($redirectId)) ? $redirectId : $this->redirects_with_domain_id;
		// $ruleId = (isset($ruleId)) ? $ruleId : $this->based_off_rule->rule_id;
		// $mainDomain = (isset($mainDomain)) ? $mainDomain : $this->domain->domain;
		// $redirectDomain = (isset($redirectDomain)) ? $redirectDomain : $this->redirect_domain->domain;

		$domainRelationships = TableRegistry::getTableLocator()->get('DomainRelationships');
		$relationship = $domainRelationships->getRelationship($domainId, $redirectId);
		$times = ($relationship->count == 1) ? 'time' : 'times';

		$messages = Router::url(['controller' => 'DomainRelationships', 'action' => 'messages', $relationship->id]);
		$link = Router::url(['controller' => 'Rules', 'action' => 'edit', 'id' => $ruleId]);
		$domain = Router::url(['controller' => 'Domains', 'action' => 'relationships', $mainDomain]);
		$reason = "<a href='{$domain}'>{$mainDomain}</a> has been part of redirects with {$redirectDomain} <b><a href='{$messages}'>{$relationship->count}</a></b> {$times} <a href='{$link}'>(rule {$ruleId})</a>";

		$this->systemReason .= '<div>' . $reason . '</div>';
	}

	public function recursiveReason($system_rule)
	{
		$domainRelationships = TableRegistry::getTableLocator()->get('DomainRelationships');
		$relationship = $domainRelationships->getRelationship($system_rule->domain_id, $system_rule->redirects_with_domain_id);
		$times = ($relationship->count == 1) ? 'time' : 'times';

		$messages = Router::url(['controller' => 'DomainRelationships', 'action' => 'messages', $relationship->id]);
		$ruleId = (!empty($system_rule->based_off_rule)) ? $system_rule->based_off_rule->rule_id : $system_rule->rule_condition_set->rule_id;
		$link = Router::url(['controller' => 'Rules', 'action' => 'edit', 'id' => $ruleId]);
		$domain = Router::url(['controller' => 'Domains', 'action' => 'relationships', $system_rule->domain->domain]);
		$ruleLink = " <a href='{$link}'>(rule {$ruleId})</a>";
		$showLinkAtEnd = null;
		if (empty($system_rule->based_off_rule)) {
			$showLinkAtEnd = $ruleLink;
			$ruleLink = null;
			$this->systemReason .= "<div><a href='{$domain}'>{$system_rule->domain->domain}</a> has been part of redirects with {$system_rule->redirect_domain->domain} <b><a href='{$messages}'>{$relationship->count}</a></b> {$times}{$ruleLink}, which is known to be {$this->rule->contact->nameWithState} {$showLinkAtEnd}.</div>";
		} else {
			$this->systemReason .= "<div><a href='{$domain}'>{$system_rule->domain->domain}</a> has been part of redirects with {$system_rule->redirect_domain->domain} <b><a href='{$messages}'>{$relationship->count}</a></b> {$times}{$ruleLink}.</div><br>";
		}

		if (!empty($system_rule->based_off_rule)) {
			// Get the rule that this rule is based off of
			$basedOffRule = $this->getSystemGeneratedRule($system_rule->system_generated_rule_id);
			$this->recursiveReason($basedOffRule);
		} 
	}

	public function getSystemGeneratedRule($id)
	{
		$systemGeneratedRulesTable = TableRegistry::getTableLocator()->get('SystemGeneratedRules');
		$systemRule = $systemGeneratedRulesTable->find()
		->contain(['Domains', 'RedirectDomain', 'BasedOffRule', 'RuleConditionSets', 'RuleConditionSets.RuleConditions'])
		->where(['SystemGeneratedRules.id' => $id]);
		return $systemRule->first();
	}
}