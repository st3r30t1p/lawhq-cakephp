<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Security;

class TeamMemberAccount extends Entity {

	protected function _setPassword($password)
	{
		if ($password == '') return null;
		return $this->encrypt_decrypt('encrypt', $password);
	}

	public function decryptPassword()
	{
		return $this->encrypt_decrypt('decrypt', $this->password);
	}

	protected function encrypt_decrypt($action, $string) 
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $enc_key = \Cake\Core\Configure::read('LawHQ.key');

        if ( $action == 'encrypt' ) {
        	$enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encrypt_method));
        	$crypted_token = openssl_encrypt($string, $encrypt_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
        	$output = base64_encode($crypted_token);
        	unset($string, $encrypt_method, $enc_key, $enc_iv);
        	return $output;
        } else if( $action == 'decrypt' ) {
        	list($crypted_token, $enc_iv) = explode("::", base64_decode($string));
        	$output = openssl_decrypt($crypted_token, $encrypt_method, $enc_key, 0, hex2bin($enc_iv));
        	unset($crypted_token, $encrypt_method, $enc_key, $enc_iv);
        	return $output;
        }
        return $output;
    }

	public function getFormattedAccount()
	{
		if ($this->account == 'cmecf') {
			return 'CM/ECF';
		}
		return ucfirst($this->account);
	}

	public function getState()
	{
		if ($this->account == 'pacer') {
			return 'Federal';
		}
		return $this->state->state;
	}

}