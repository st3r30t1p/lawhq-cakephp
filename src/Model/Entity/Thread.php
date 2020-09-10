<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Collection\Collection;

class Thread extends Entity {
	public function ruleIds()
	{
		if (empty($this->rule_assignments)) {
			return '-';
		}
		$collection = new Collection($this->rule_assignments);
		$ids = $collection->extract('rule_id');
		return implode(', ', array_unique($ids->toList()));
	}
}