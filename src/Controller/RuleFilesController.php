<?php
namespace App\Controller;

use App\Controller\AppController;

class RuleFilesController extends AppController
{
	public function edit($id)
	{
		$file = $this->RuleFiles->find()
		->where(['RuleFiles.id' => $id])
		->contain(['Files'])->first();

		if (!$file) {
			$this->redirect(['controller' => 'Rules', 'action' => 'index']);
		}

		if ($this->request->getData()) {
			$file->set($this->request->getData());
			$file->date_captured = date('Y-m-d', strtotime($this->request->getData()['date_captured']));

			if ($this->RuleFiles->save($file)) {
				$this->redirect(['controller' => 'Rules', 'action' => 'edit', $file->rule_id]);
			}
		}

		$this->set(compact('file'));
	}

	public function delete($id = null)
	{
	    $this->request->allowMethod(['post', 'delete']);
	    $file = $this->RuleFiles->get($id);
	    $file->is_deleted = 1;

	    if ($this->RuleFiles->save($file)) {
	        $this->Flash->success(__('The file has been deleted.'));
	    } else {
	        $this->Flash->error(__('There has been a problem deleting the file.'));
	    }

	    return $this->redirect(['controller' => 'Rules', 'action' => 'edit', $file->rule_id]);
	}
}