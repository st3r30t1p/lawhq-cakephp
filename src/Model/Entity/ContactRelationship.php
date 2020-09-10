<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ContactRelationship extends Entity 
{
	public function formatRelationship()
	{
		if (in_array($this->relationship, ['ceo', 'cmo', 'coo', 'cfo'])) {
			return strtoupper($this->relationship);
		}

		return ucwords(str_replace('_', ' ', $this->relationship));
	}

	public function showErrors()
	{
		$errorMessage = '';
		foreach ($this->errors() as $error) {
			foreach ($error as $key => $message) {
				$errorMessage .= '<li>' . $message . '</li>';
			}
		}
		return '<ul style="margin-left: 430px;list-style: disc;">' . $errorMessage . '</ul>';
	}
}