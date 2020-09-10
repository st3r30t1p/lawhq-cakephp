<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Event\Event;
use ArrayObject;

class Contact extends Entity
{
	public function _getName()
	{
		if ($this->type == 'person')
			return $this->person_first_name . ' ' . $this->person_last_name;
		else
			return $this->company_name;
	}

	public function _getNameWithState()
	{
		if ($this->type == 'person')
			return $this->person_first_name . ' ' . $this->person_last_name;
		else
			return  "{$this->company_name} {$this->getCompanyIncIn()}";
	}

	public function getPersonStateOrCompanyIncIn() 
	{
		if ($this->type == 'company' && !empty($this->company_incorporated_in))
			return '(' . $this->company_incorporated_in . '-' . substr($this->company_domestic_foreign, 0, 1) . ')';
		else if ($this->type == 'person' && isset($this->contact_addresses[0]) && isset($this->contact_addresses[0]->state->code))
			return '(' . $this->contact_addresses[0]->state->code . ')';
		else return '';
	}

	public function getCompanyIncIn()
	{
		if ($this->type == 'company' && !empty($this->company_incorporated_in))
			return '(' . $this->company_incorporated_in . '-' . substr($this->company_domestic_foreign, 0, 1) . ')';
		else
			return '';
	}

	public function isDomestic() {
		if ($this->company_domestic_foreign == 'domestic')
			return true;
		else
			return false;
	}

	public function getAddress()
	{
		if (empty($this->contact_addresses[0]->address_1)) {
			return '-';
		} else if ($this->contact_addresses[0]->state) {
			$address = $this->contact_addresses[0];
			return $address->address_1 . ', ' . $address->city . ' ' . strtoupper($address->state->code) . ' ' . $address->zip;
		} else {
			$address = $this->contact_addresses[0];
			return $address->address_1 . ', ' . $address->city . ' ' . $address->zip;
		}
	}

	public function getEmail()
	{
		if (empty($this->contact_emails[0]->email))
			return '-';
		else
			return $this->contact_emails[0]->email;
	}

	public function getPhoneNumber()
	{
		if (empty($this->contact_phone_numbers[0]->phone_number))
			return '-';
		else
			return $this->contact_phone_numbers[0]->phone_number;
	}

	public function primaryAddress()
	{
		if (!isset($this->primary_address)) {
			return '-';
		} else {
			$address = $this->primary_address;
			return $address->address_1 . ', ' . $address->city . ' ' . $address->state . ' ' . $address->zip;
		}
	}

	public function primaryEmail()
	{
		if (!isset($this->primary_email))
			return '-';
		else
			return $this->primary_email->email;
	}

	public function primaryPhoneNumber()
	{
		if (!isset($this->primary_phone))
			return '-';
		else
			return $this->primary_phone->phone_number;
	}

	protected function _setPersonFirstName($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setPersonMiddleName($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setPersonLastName($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setPersonDob($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setSsn($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setCompanyName($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setCompanyIncorporatedIn($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setCompanyDomesticForeign($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setCompanyRegistrationNumber($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}

	protected function _setFein($value)
	{
	      if($value == '') {
	           $value = null;
	       }

	       return $value;
	}
}
