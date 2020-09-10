<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class RuleCondition extends Entity
{
	public function getAbbrev()
	{
		$firstChar = substr($this->type, 0, 1);

		if ($firstChar == 'p') {
			return '#';
		} else if ($this->type == 'ips') {
			return 'IP';
		} else if($this->type == 'reg_name') {
			return 'RN';
		} else if ($this->type == 'reg_email') {
			return 'RE';
		}

		return ucfirst($firstChar);
	}
}
