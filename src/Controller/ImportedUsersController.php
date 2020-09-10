<?php
namespace App\Controller;

use App\Controller\AppController;

class ImportedUsersController extends AppController
{
	public function index()
	{
		$users = $this->ImportedUsers->find();
		$users->select($this->ImportedUsers);
		$users->select(['messages' => $users->func()->count('*')])
		->leftJoinWith('threads')
		->group(['threads.imported_user_id'])
		->order(['messages' => 'DESC']);

		$this->set(compact('users'));
	}
}
