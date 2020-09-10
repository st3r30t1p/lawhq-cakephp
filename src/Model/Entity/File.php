<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Lib\UrlParts;

class File extends Entity {

	public function getFilePath() {
		return ROOT . DS . '..' . DS . 'uploaded_files' . DS . $this->sha1;
	}

	public function getUrl() {
		return \Cake\Routing\Router::url("/files/get/{$this->sha1}");
	}
}