<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;

class SystemGeneratedRule extends Entity
{
	public function generateReason()
	{	
		$domainRelationships = TableRegistry::getTableLocator()->get('DomainRelationships');
		$reason = '';
		$relationship = $domainRelationships->getRelationship($this->domain_id, $this->redirects_with_domain_id);
		$times = ($relationship->count == 1) ? 'time' : 'times';
		$redirects = '';
			foreach ($this->rule_condition_set->rule_conditions as $key => $condition) {
				if ($condition->type != 'domain') {
					continue;
				}
				if (count($this->rule_condition_set->rule_conditions) == 1) {
					$redirects .= "with {$condition->search_for}";
				} else {				
					if ($key == 0) {
						$redirects .= "where domain contains {$condition->search_for}";
					} else {
						$redirects .= " and {$condition->search_for}";
					}
				}
			}
			$messages = Router::url(['controller' => 'DomainRelationships', 'action' => 'messages', $relationship->id]);
			$rule = Router::url(['controller' => 'Rules', 'action' => 'edit', 'id' => $this->rule_condition_set->rule_id]);
			$domain = Router::url(['controller' => 'Domains', 'action' => 'relationships', $this->domain->domain]);
			$reason .= "<a href='{$domain}'>{$this->domain->domain}</a> has been part of redirects {$redirects} <b><a href='{$messages}'>{$relationship->count}</a></b> {$times} <a href='{$rule}'>(rule {$this->rule_condition_set->rule_id}).</a>";
		return trim($reason);
	}

	public function generateAltReason($domainId = null, $redirectId = null, $ruleId = null, $mainDomain = null, $redirectDomain = null)
	{
		$domainId = (isset($domainId)) ? $domainId : $this->domain_id;
		$redirectId = (isset($redirectId)) ? $redirectId : $this->redirects_with_domain_id;
		$ruleId = (isset($ruleId)) ? $ruleId : $this->based_off_rule->rule_id;
		$mainDomain = (isset($mainDomain)) ? $mainDomain : $this->domain->domain;
		$redirectDomain = (isset($redirectDomain)) ? $redirectDomain : $this->redirect_domain->domain;

		$domainRelationships = TableRegistry::getTableLocator()->get('DomainRelationships');
		$relationship = $domainRelationships->getRelationship($domainId, $redirectId);
		$times = ($relationship->count == 1) ? 'time' : 'times';

		$messages = Router::url(['controller' => 'DomainRelationships', 'action' => 'messages', $relationship->id]);
		$link = Router::url(['controller' => 'Rules', 'action' => 'edit', 'id' => $ruleId]);
		$domain = Router::url(['controller' => 'Domains', 'action' => 'relationships', $mainDomain]);
		$reason = "<a href='{$domain}'>{$mainDomain}</a> has been part of redirects with {$redirectDomain} <b><a href='{$messages}'>{$relationship->count}</a></b> {$times} <a href='{$link}'>(rule {$ruleId})</a>";

		return $reason;
	}

	public function generateRecursiveReason()
	{
		$domainRelationships = TableRegistry::getTableLocator()->get('DomainRelationships');
		$relationship = $domainRelationships->getRelationship($this->domain_id, $this->redirects_with_domain_id);
		$times = ($relationship->count == 1) ? 'time' : 'times';

		$ruleId = (!empty($this->based_off_rule)) ? $this->based_off_rule->rule_id : $this->rule_condition_set->rule_id;
		$messages = Router::url(['controller' => 'DomainRelationships', 'action' => 'messages', $relationship->id]);
		$link = Router::url(['controller' => 'Rules', 'action' => 'edit', 'id' => $ruleId]);
		$domain = Router::url(['controller' => 'Domains', 'action' => 'relationships', $this->domain->domain]);

		$ruleLink = " <a href='{$link}'>(rule {$ruleId})</a>";
		$showLinkAtEnd = null;
		if (empty($this->based_off_rule)) {
			$showLinkAtEnd = $ruleLink;
			$ruleLink = null;
		}

		$reason = "<a href='{$domain}'>{$this->domain->domain}</a> has been part of redirects with {$this->redirect_domain->domain} <b><a href='{$messages}'>{$relationship->count}</a></b> {$times}{$ruleLink}";
		if (empty($this->based_off_rule)) {
			$reason .= ", which is known to be {$this->rule_condition_set->rule->contact->nameWithState} {$showLinkAtEnd}.";
			return $reason;
		} else {
			$reason .= '.<br><br>' . $this->based_off_rule->generateRecursiveReason();
		}

		return $reason;
	}

	public function findUserRulesThatDomainRedirectsWith()
	{
		return $this->queryDomainRelationships($this->domain_id, $this->redirects_with_domain_id, $this->domain->domain);
	}

	public function queryDomainRelationships($domainId, $redirectDomainId, $domain)
	{
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
		 $reason = '';
		 foreach ($redirects as $redirect) {
		 	$reason .= '<div>' . $this->generateAltReason($domainId, $redirect['id'], $redirect['rule_id'], $domain, $redirect['search_for']) . '.</div>';
		 }
		 return $reason;
	}

	public function getContactName()
	{
		if (isset($this->rule_condition_set)) {
			return $this->rule_condition_set->rule->contact->nameWithState;
		} else {
			return $this->based_off_rule->rule->contact->nameWithState;
		}
	}

	public function getContactId()
	{
		if (isset($this->rule_condition_set)) {
			return $this->rule_condition_set->rule->contact->id;
		} else {
			return $this->based_off_rule->rule->contact->id;
		}
	}
}