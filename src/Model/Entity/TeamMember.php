<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Lib\UrlParts;

class TeamMember extends Entity {

	protected function _setPassword($password) {
		if ($password == '') return null;
		$this->password_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
		return null;
	}

	public function verifyPassword($password) {
		if (!password_verify($password, $this->password_hash)) {
			return false;
		}
		if (true || password_needs_rehash($this->password_hash, PASSWORD_DEFAULT, ['cost' => 10])) {
			$this->password = $password;
		}
		return true;
	}

	protected function _getFullName()
	{
	    return $this->first_name . ' ' . $this->last_name;
	}

	protected function _getExtension()
	{
		return '3' . sprintf("%04d", $this->id);
	}

	public function formattedNumber()
	{
		$num = $this->phone_number;
		if (strlen($num) != 10) {
			return 'Invalid Number';
		}
		return substr($num, 0, 3).'-'.substr($num, 3, 3).'-'.substr($num,6);
	}
}