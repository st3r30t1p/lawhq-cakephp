<?php
namespace App\Controller;

use App\Controller\AppController;

class ContactRelationshipsController extends AppController
{
	public function swap($id = null)
	{
		$this->viewBuilder()->setLayout('ajax');
		$contactId = $this->request->Query('contact');
		if (empty($id) || empty($contactId)) { return $this->redirect(['action' => 'index']); }

		$relationship = $this->ContactRelationships->get($id);
		$relationshipContactId = $relationship->contact_id;
		$relationshipContactIdTarget = $relationship->contact_id_target;
		$relationship->contact_id = $relationshipContactIdTarget;
		$relationship->contact_id_target = $relationshipContactId;
		if ($this->ContactRelationships->save($relationship)) {
			$this->redirect(['controller' => 'Contacts', 'action' => 'edit', $contactId, '#' => 'relationships']);
		}
	}
}