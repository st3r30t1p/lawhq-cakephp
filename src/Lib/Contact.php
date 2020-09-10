<?php
namespace App\Lib;
use Cake\Utility\Text;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Class Contact
{
	function __construct($contact, $contactTable)
	{
		$this->contact = $contact;
		$this->contactTable = $contactTable;
		$this->registered_agents = [];
		$this->foreign_entities = [];
		$this->can_sue_in = [];
		$this->present_in = [];
		$this->where_to_serve = [
			'Principal Place of Business' => [],
			'Domestic Registered Agent' => [],
			'Foreign Registered Agents' => []
		];
		
		$this->relationshipGroups = [
			'individual' => [],
			'registered_agent' => [],
			'foreign_entities' => [],
			'domestic_company' => [],
			'other' => []
		];

		$this->setPpob();
		$this->getRegisteredAgentsAndForeignEntities();
		$this->lookupRegisteredAgent();
		$this->lookupForeignEntites();
		$this->formatRelationships();
	}

	public function setPpob()
	{
		foreach ($this->contact->contact_addresses as $key => $address) {
			$this->contact->contact_addresses[$key]->name = $this->contact->company_name;

			if ($address->type == 'ppob') {
				$this->where_to_serve['Principal Place of Business'][] = $address;
				array_push($this->can_sue_in, $address->state->state);
				array_push($this->present_in, $address->state->state);
				break;
			}
		}

		if ($this->contact->company_domestic_foreign == 'domestic' && isset($this->contact->States)) {
			array_push($this->can_sue_in, $this->contact->States['state']);
			array_push($this->present_in, $this->contact->States['state']);
		}
	}

	public function getRegisteredAgentsAndForeignEntities()
	{	
		// Loop over relationships and get contact ids of registered agents and foreign entities
		foreach ($this->contact->contact_relationships as $relationship) {

			$relationshipType = $relationship->relationship;

			if ($this->contact->id != $relationship->contact_id) {
				
				$contact_id = $relationship->contact_id;

				if ($relationshipType == 'registered_agent' && $this->contact->company_domestic_foreign == 'domestic') {
					array_push($this->registered_agents, $contact_id);
				}

				if ($relationshipType == 'foreign_entity') {
					array_push($this->foreign_entities, $contact_id);
				}
			}
		}
	}

	public function lookupRegisteredAgent()
	{
		// Lookup the registered agent of this contact and get the primary address
		if (empty($this->registered_agents)) return;

		$registered_agent = $this->contactTable->find()
		->where(['Contacts.id' => $this->registered_agents[0], 'Contacts.is_deleted IS NULL'])
		->contain(['primaryAddresses', 'primaryAddresses.States', 'States'])->first();

		if ($registered_agent && isset($registered_agent->primary_address)) {
			$registered_agent->primary_address->name = $registered_agent->company_name;
			array_push($this->where_to_serve['Domestic Registered Agent'], $registered_agent->primary_address);
		}
	}

	public function lookupForeignEntites()
	{
		// Lookup the forieign entities and then look up the registered agent of each foreign entity
		if (empty($this->foreign_entities)) return;

		$foreign_ids = implode(', ', $this->foreign_entities);
		$foreign_entities_registered_agents = $this->contactTable->contactRelationships->find()
		->select(['contact_id'])
		->where(["contact_id_target IN ({$foreign_ids})", 'relationship' => 'registered_agent', 'is_deleted IS NULL']);

		foreach ($foreign_entities_registered_agents as $entity) {

			$registered_agent = $this->contactTable->find()
			->where(['Contacts.id' => $entity->contact_id])
			->contain(['primaryAddresses', 'primaryAddresses.States', 'States'])->first();

			if ($registered_agent && isset($registered_agent->primary_address)) {
				$registered_agent->primary_address->name = $registered_agent->company_name;
				array_push($this->where_to_serve['Foreign Registered Agents'], $registered_agent->primary_address);

				$state = $registered_agent->primary_address->state;
				if (isset($state) && !in_array($state->state, $this->present_in)) {
					array_push($this->present_in, $state->state);
				}
			}
		}
	}

	public function formatRelationships()
	{
		foreach ($this->contact->contact_relationships as $key => $relationship) {

			if ($this->contact->type == 'person') {
				$group = 'individual';
			} else {
				if ($relationship->relationship == 'registered_agent') {
					$group = 'registered_agent';
				} else if ($relationship->relationship == 'foreign_entity') {
					$group = ($this->contact->company_domestic_foreign == 'foreign') ? 'domestic_company' : 'foreign_entities';
				} else {
					$group = 'other';
				}
			}

			$contactId = ($relationship->contact_id == $this->contact->id) ? $relationship->contact_id_target : $relationship->contact_id;
			$contact = $this->contactTable->get($contactId);

			if ($contact->is_deleted) {
				continue;
			}
			// If viewing ContactIDTarget then "Relationship is", else if viewing ContactID then "Relationship of
			$isOf = ($this->contact->id == $relationship->contact_id_target) ? ' is' : ' of';
			$this->relationshipGroups[$group][$key]['type'] = $relationship->formatRelationship() . $isOf;
			$this->relationshipGroups[$group][$key]['contact_name'] = $contact->name;
			$this->relationshipGroups[$group][$key]['contact_url'] = Router::url(['controller' => 'Contacts', 'action' => 'view', 'id' => $contactId]);
			$this->relationshipGroups[$group][$key]['incorporated_in'] = $contact->company_incorporated_in;
		}

		// Alphabetize by relationship type then incorporated in
		foreach ($this->relationshipGroups as $key => $group) {
			usort($this->relationshipGroups[$key], function($a, $b) {
			    return $a['incorporated_in'] <=> $b['incorporated_in'];
			    return $a['type'] <=> $b['type'];
			});
		}

	}

	public function canSueIn()
	{
		if (empty($this->can_sue_in)) {
			return '-';
		}

		usort($this->can_sue_in, function ($a, $b) {
		    return $a <=> $b;
		});

		return Text::toList($this->can_sue_in);
	}

	public function presentIn()
	{
		if (empty($this->present_in)) {
			return '-';
		}

		usort($this->present_in, function ($a, $b) {
		    return $a <=> $b;
		});

		return Text::toList($this->present_in);
	}
}
