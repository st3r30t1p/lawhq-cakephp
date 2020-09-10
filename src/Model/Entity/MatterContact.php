<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class MatterContact extends Entity
{
	public function formatType()
	{
		if (strpos($this->type, '_plaintiff') || strpos($this->type, '_defendant')) {
			$plainitiffOrDefendant = (strpos($this->type, '_plaintiff')) ? 'P' : 'D';

			$explodeType = explode('_', $this->type);
			return ucfirst($explodeType[0]) . "($plainitiffOrDefendant)";
		}

		return ucwords(str_replace('_', ' ', $this->type));
	}

	public function getName()
	{
		if (isset($this->team_member)) {
			return $this->team_member->full_name;
		} else if (isset($this->imported_user)) {
			return $this->imported_user->full_name;
		}
		return null;
	}

    public function getContactName()
    {
        if (isset($this->contact)) {
            return $this->contact->name;
        }
        return null;
    }

	public function getAddress()
	{
		if (isset($this->contact))
			return $this->contact->primaryAddress();
		else if (isset($this->imported_user))
			return $this->imported_user->address;
		else
			return '-';
	}

	public function getPhone()
	{
		if (isset($this->contact))
			return $this->contact->primaryphoneNumber();
		else if (isset($this->imported_user))
			return $this->imported_user->phoneNumber;
		else
			return '-';
	}

	public function getEmail()
	{
		if (isset($this->contact))
			return $this->contact->primaryEmail();
		else if (isset($this->imported_user))
			return $this->imported_user->email;
		else
			return $this->team_member->username;
	}

	protected function _setImportedUserId($value)
	{
		if($value == '') {
			$value = null;
		}

		return $value;
	}

	protected function _setTitle($value)
	{
		if($value == '') {
			$value = null;
		}

		return $value;
	}
}
