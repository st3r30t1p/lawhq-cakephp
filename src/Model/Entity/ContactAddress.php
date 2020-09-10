<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Event\Event;
use ArrayObject;

class ContactAddress extends Entity 
{
	public function formattedType()
	{
		if ($this->type == 'ppob')
			return strtoupper($this->type);
		else
			return ucfirst($this->type);
	}
}