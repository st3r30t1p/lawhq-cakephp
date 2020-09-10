<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;


class PacerAccount extends Entity {
	
	public function _getFormattedType()
	{
	    if ($this->type == 'pacer')
	        return ucfirst($this->type);
	    else
	        return strtoupper($this->type);
	}
}