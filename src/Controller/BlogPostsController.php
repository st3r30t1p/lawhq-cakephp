<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class BlogPostsController extends AppController
{
	public function index()
	{
		$blogPosts = $this->BlogPosts->find()
		->contain(['TeamMembers']);

		$this->set(compact('blogPosts'));
	}

	public function add()
	{
		$filesTable = TableRegistry::getTableLocator()->get('Files');

		if ($this->request->getData()) {
			$blogPost = $this->BlogPosts->newEntity($this->request->getData());
			$blogPost->slug = $blogPost->title;
			$blogPost->excerpt = $blogPost->body;

			if (!empty($this->request->getData()['cover_img']['name'])) {
				$img = $this->request->getData()['cover_img']; //put the data into a var for easy use
				$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
				$setNewFileName = time() . "_" . rand(000000, 999999);

				//only process if the extension is valid
				if (in_array($ext, ['jpg', 'jpeg', 'png'])) {

					$sha1 = sha1_file($img['tmp_name']);
					$filename = ROOT . DS . '..' . DS . 'uploaded_files' . DS . $sha1;
					if (!file_exists($filename) || sha1_file($filename) != $sha1) {
						move_uploaded_file($img['tmp_name'], $filename);
					}

					$file = $filesTable->newEntity();
					$file->filename = "blog_{$setNewFileName}.{$ext}";
					$file->size = $img['size'];
					$file->sha1 = $sha1;
					$file->created = Time::now();
					$filesTable->save($file);

				    //prepare the filename for database entry
				    $blogPost->cover_img = $setNewFileName . '.' . $ext;
				}
			}

			if ($this->BlogPosts->save($blogPost)) {
				return $this->redirect(['action' => 'index']);
			}
		}

		$this->set('users', $this->BlogPosts->TeamMembers->find('list'));
	}

	public function edit($id)
	{
		$filesTable = TableRegistry::getTableLocator()->get('Files');

		$blogPost = $this->BlogPosts->find()
		->where(['BlogPosts.id' => $id])
		->contain(['TeamMembers'])->first();

		if ($this->request->getData()) {
			$blogPost = $this->BlogPosts->patchEntity($blogPost, $this->request->getData());
			$blogPost->excerpt = $blogPost->body;

			if (!empty($this->request->getData()['cover_img']['name'])) {
				$img = $this->request->getData()['cover_img']; //put the data into a var for easy use
				$ext = substr(strtolower(strrchr($img['name'], '.')), 1); //get the extension
				$setNewFileName = time() . "_" . rand(000000, 999999);

				//only process if the extension is valid
				if (in_array($ext, ['jpg', 'jpeg', 'png'])) {

					$sha1 = sha1_file($img['tmp_name']);
					$filename = ROOT . DS . '..' . DS . 'uploaded_files' . DS . $sha1;
					if (!file_exists($filename) || sha1_file($filename) != $sha1) {
						move_uploaded_file($img['tmp_name'], $filename);
					}

					$file = $filesTable->newEntity();
					$file->filename = "blog_{$setNewFileName}.{$ext}";
					$file->size = $img['size'];
					$file->sha1 = $sha1;
					$file->created = Time::now();
					$filesTable->save($file);

				    //prepare the filename for database entry
				    $blogPost->cover_img = $setNewFileName . '.' . $ext;
				}
			}

			if (empty($blogPost->cover_img)) {
				unset($blogPost->cover_img);
			}

			if ($this->BlogPosts->save($blogPost)) {
				return $this->redirect(['action' => 'index']);
			}
		}

		$this->set(compact('blogPost'));
		$this->set('users', $this->BlogPosts->TeamMembers->find('list'));
	}

	public function uploadImage()
	{	
		$this->viewBuilder()->setLayout('ajax');


		$filesTable = TableRegistry::getTableLocator()->get('Files');

		$img = $_FILES["upload"]; //put the data into a var for easy use

		if (!empty($img)) {
			$ext = substr(strtolower(strrchr($img['name'], '.')), 1); //get the extension
			$setNewFileName = time() . "_" . rand(000000, 999999);

			//only process if the extension is valid
			if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) {

				$sha1 = sha1_file($img['tmp_name']);
				$filename = ROOT . DS . '..' . DS . 'uploaded_files' . DS . $sha1;
				if (!file_exists($filename) || sha1_file($filename) != $sha1) {
					move_uploaded_file($img['tmp_name'], $filename);
				}

				$file = $filesTable->newEntity();
				$file->filename = "blog_{$setNewFileName}.{$ext}";
				$file->size = $img['size'];
				$file->sha1 = $sha1;
				$file->created = Time::now();

				if ($filesTable->save($file)) {
					$json = json_encode([
					    'uploaded' => 1,
					    'fileName' => "{$setNewFileName}.{$ext}",
					    'url' => "https://lawhq.com/blog-posts/image/{$setNewFileName}.{$ext}",
					]);

					$this->set(compact('json'));
				}
			}
		}

	}
}