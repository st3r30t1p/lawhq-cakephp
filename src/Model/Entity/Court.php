<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Court extends Entity
{
	protected function _setFjcCourtId($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setUrl($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}
}
