<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class FilesController extends AppController {

	public function get($sha1) {
		// if ($this->getRequest()->getSession()->read('User.id') === null) {
		//     return $this->redirect(['controller' => 'TeamMembers', 'action' => 'login']);
		// }
		$filesTable = TableRegistry::getTableLocator()->get('Files');
		$file = $filesTable->find()->where(['sha1' => $sha1])->first();
		$parts = explode('.', $file->filename);
		$ext = array_pop($parts);
		if ($ext == 'png') {
			header('Content-Type: image/png');
		} else if ($ext == 'jpg') {
			header('Content-Type: image/jpeg');
		} else if ($ext == 'pdf') {
			header('Content-type: application/pdf');
		} else if ($ext == 'mp4') {
			header('Content-type: video/mp4');
		} else {
			header('Content-Type: application/octet-stream');
			if (strpos($file->filename, 'rule') >= 0) {
				header('Content-disposition: attachment; filename=' . $file->filename);
			}
		}
		header('Content-Length: ' . filesize(ROOT . DS . '..' . DS . 'uploaded_files' . DS . $file->sha1));
		readfile(ROOT . DS . '..' . DS . 'uploaded_files' . DS . $file->sha1);
		exit();
	}
}