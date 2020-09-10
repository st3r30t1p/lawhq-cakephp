<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Lib\UrlParts;

class ImportedUser extends Entity 
{
	protected function _getFullName()
	{
	    return $this->name_firstName . ' ' . $this->name_lastName;
	}

	protected function _getAddress()
	{
	    return trim($this->address_address . ' ' . $this->address_address2) . ', ' . $this->address_city . ' ' . $this->address_state . ' ' . $this->address_zip;
	}
}