<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Event\Event;
use ArrayObject;

class ContactWebsite extends Entity 
{
	public function addHttp($website)
	{
		 if (!preg_match("~^(?:f|ht)tps?://~i", $this->website)) { 
		 	$website = "http://" . $this->website; 
		 } 

		 return $website; 
	}
}