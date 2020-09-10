<?php
namespace App\View\Helper;

use Cake\View\Helper;

class PhoneHelper extends Helper {
	public function format($phoneNumber) {
		if (preg_match('/(\+1)?\D*(\d{3})\D*(\d{3})\D*(\d{4})/', $phoneNumber, $matches) > 0) {
			return "{$matches[2]}-{$matches[3]}-{$matches[4]}";
		}
		return $phoneNumber;
	}
}