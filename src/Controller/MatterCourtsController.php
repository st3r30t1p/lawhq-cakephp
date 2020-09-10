<?php
namespace App\Controller;

use App\Controller\AppController;

class MatterCourtsController extends AppController
{
	public function add()
	{
		$matterCourt = $this->MatterCourts->newEntity($this->request->getData());

		if ($this->MatterCourts->save($matterCourt)) {
			$this->redirect(['controller' => 'Matters', 'action' => 'edit', 'id' => $matterCourt->matter_id]);
		}
	}
}