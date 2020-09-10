<?php
namespace App\Controller;

use App\Service\Docket\DocketService;
use App\Service\Docket\ParseDocketCourtsService;
use App\Service\Docket\ParseDocketEmailService;
use Cake\ORM\TableRegistry;

class ApiController extends AppController
{
	public function contactsSearch()
	{
		$this->viewBuilder()->setLayout('ajax');
		$contacts = TableRegistry::getTableLocator()->get('Contacts');

		$contactName = trim($this->request->getquery('q'));
		$contacts = $contacts->find()
		->where(['concat(person_first_name, " ", person_last_name) LIKE' => "%{$contactName}%"])
		->orWhere(['company_name LIKE' => "%{$contactName}%"])
		->where(['is_deleted IS NULL'])
		->order(['company_incorporated_in' => 'ASC'])
		->limit(60);

		$this->set('contacts', $contacts);
	}

	public function addContactInfo()
	{
		$this->viewBuilder()->setLayout('ajax');
		$contacts = TableRegistry::getTableLocator()->get('Contacts');

		$countries = $contacts->contactAddresses->Countries->find('list', [
			'keyField' => 'code',
			'valueField' => 'nicename',
		])->all();

		$states = $contacts->States->find('list', [
			'keyField' => 'code',
			'valueField' => 'state',
		])->all();

		$this->set('contacts', $contacts->allList());
		$this->set('key', $this->request->getquery('array-index'));
		$this->set('form', $this->request->getquery('form'));
		$this->set('isContactNew', $this->request->getquery('is-contact-new'));
		$this->set(compact('states'));
		$this->set(compact('countries'));
	}

	public function removeContactInfo()
	{
		$this->viewBuilder()->setLayout('ajax');
		$table = TableRegistry::getTableLocator()->get( $this->request->getquery('table') );

		$contactInfo = $table->get( $this->request->getquery('table-id') );
		$contactInfo->is_deleted = 1;
		$table->save($contactInfo);
	}

	public function addNote()
	{
		$this->viewBuilder()->setLayout('ajax');
		if (empty($this->request->getquery('table')) || empty($this->request->getquery('note'))) return false;

		$table = $this->request->getquery('table');
		$fieldToSaveId = $this->request->getquery('field');

		$notesTable = TableRegistry::getTableLocator()->get( $table );
		$note = $notesTable->newEntity();

		$note->note = trim($this->request->getquery('note'));
		$note->team_member_id = $this->appUser->id;
		$note->$fieldToSaveId = $this->request->getquery('id');
		$notesTable->save($note);

		$noteLookup = $notesTable->find()
		->where(["{$table}.id" => $note->id])
		->contain(['TeamMembers'])->first();

		$this->set('note', $noteLookup);
	}

	public function editNote()
	{
		$this->viewBuilder()->setLayout('ajax');
		if (empty($this->request->getquery('note-id')) || empty($this->request->getquery('table')) || empty($this->request->getquery('note'))) return false;

		$fieldToSaveId = $this->request->getquery('field');

		$notesTable = TableRegistry::getTableLocator()->get( $this->request->getquery('table') );
		$origNote = $notesTable->get( $this->request->getquery('note-id') );
		$origNote->is_deleted = 1;
		$notesTable->save($origNote);

		$newNote = $notesTable->newEntity();
		$newNote->$fieldToSaveId = $origNote->$fieldToSaveId;
		$newNote->team_member_id = $this->appUser->id;
		$newNote->note = trim($this->request->getquery('note'));
		$newNote->edit_note_id = $origNote->id;
		$newNote->created = $origNote->created;
		$notesTable->save($newNote);

		$this->set('note', $newNote->note);
	}

	public function deleteNote()
	{
		$this->viewBuilder()->setLayout('ajax');
		if (empty($this->request->getquery('note-id')) || empty($this->request->getquery('table'))) return false;

		$notesTable = TableRegistry::getTableLocator()->get( $this->request->getquery('table') );
		$note = $notesTable->get( $this->request->getquery('note-id') );
		$note->is_deleted = 1;
		$notesTable->save($note);
	}

	public function lookupForeignEntities()
	{
		$this->viewBuilder()->setLayout('ajax');
		if (empty($this->request->getquery('id'))) return false;

		$relationshipsTable = TableRegistry::getTableLocator()->get('contactRelationships');
		$foreignEntities = $relationshipsTable->find()
		->where(['contact_id_target' => $this->request->getquery('id'), 'relationship' => 'foreign_entity', 'contactRelationships.is_deleted IS NULL'])
		->contain(['Contacts', 'contacts.contactAddresses'])
		->order(['Contacts.company_incorporated_in' => 'ASC']);

		$this->set(compact('foreignEntities'));
		$this->set('domesticId', $this->request->getquery('id'));
	}

	public function matterContactSearch()
	{
		// Always lookup contacts, except for:
		// Plainitiff (users)
		// Attorney_for_Plainitiff (team_members)
		// Paralegal_for_Plainitiff (team_members)

		$this->viewBuilder()->setLayout('ajax');
		if (empty($this->request->getquery('q'))) return false;

		$q = $this->request->getquery('q');
		$type = $this->request->getquery('type');
		$table = 'Contacts';

		if ($type == 'plaintiff') {
			$table = 'ImportedUsers';
		} else if (in_array($type, ['attorney_for_plaintiff', 'paralegal_for_plaintiff'])) {
			$table = 'TeamMembers';
		}

		$search = TableRegistry::getTableLocator()->get($table)->find();

		if ($table == 'Contacts') {
			$search->where(["(concat(person_first_name, ' ', person_last_name) LIKE '%{$q}%' OR company_name like '%{$q}%')"])
			->andWhere(['(company_domestic_foreign !="foreign" OR company_domestic_foreign IS NULL)']);
		} else if ($table == 'ImportedUsers') {
			$search->where(["concat(name_firstName, ' ', name_lastName) LIKE '%{$q}%'"]);
		} else {
			$search->where(["concat(first_name, ' ', last_name) LIKE '%{$q}%'"]);
		}

		$this->set(compact('table'));
		$this->set('searchresults', $search);
	}

	public function addRelationship()
	{
		$this->viewBuilder()->setLayout('ajax');
		$jsonRelationships = json_decode($this->request->getquery('json'), true);
		$relationshipsTable = TableRegistry::getTableLocator()->get('contactRelationships');

		$returnData = [
			'errors' => false,
			'fix' => []
		];

		foreach ($jsonRelationships as $relationship) {
		    // Check to see if values are empty
		    // if (empty($relationship['contact_id_target']) || empty($relationship['relationship'])) {
		    // 	$returnData['fix'][$relationship['key']] = 'Please fill in both fields.';
		    // 	continue;
		    // }

			// Foreign Entity - Can only have a Registered Agent and Foreign Entity relationship. Nothing else.
		    if ($relationship['contact_entity_type'] == 'foreign' && !in_array($relationship['relationship'], ['registered_agent', 'foreign_entity'])) {
		    	$returnData['fix'][$relationship['key']] = 'Foreign entities can only have a Registered Agent and Foreign Entity relationship';
		    	continue;
		    }

		  	// Check to see if this relationship already exists
		    // $dupCheck = ['contact_id' => $relationship['contact_id'], 'relationship' => $relationship['relationship'], 'contact_id_target' => $relationship['contact_id_target'], 'is_deleted IS NULL'];

		    // if (!empty($relationship['id'])) {
		    // 	$dupCheck['id !='] = $relationship['id'];
		    // }

		    // if ($relationshipsTable->exists($dupCheck)) {
		    // 	$returnData['fix'][$relationship['key']] = 'This relationship already exists.';
		    // 	continue;
		    // }

		    // Check if this contact already has a registed agent
		    if ($relationship['relationship'] == 'registered_agent' && empty($relationship['id'])) {
		        if ($relationshipsTable->exists(['relationship' => 'registered_agent', 'contact_id_target' => $relationship['contact_id_target'], 'is_deleted IS NULL'])) {
		            $returnData['fix'][$relationship['key']] = 'This contact already has a registered agent.';
		            continue;
		        }
		    }

		    // A foreign entity can only have one Foreign Entity relationship (and one Registered Agent relationship which happens above)
	    	if ($relationship['contact_entity_type'] == 'foreign' && empty($relationship['id'])) {
	    	    if ($relationshipsTable->exists(['relationship' => 'foreign_entity', 'contact_id' => $relationship['contact_id'], 'is_deleted IS NULL'])) {
	    	        $returnData['fix'][$relationship['key']] = 'This contact already has a foreign entity.';
	    	        continue;
	    	    }
	    	}

	    	// Subsidiary - ContactID and ContactIDTarget must be domestic company
	    	if ($relationship['relationship'] == 'subsidiary') {
	    		if ($relationship['contact_entity_type'] != 'domestic' && $relationship['contact_target_entity_type'] != 'domestic') {
	    			$returnData['fix'][$relationship['key']] = 'Both contacts must be domestic for subsidiary relationship.';
	    			continue;
	    		}
	    	}

	    	// Stockholder - ContactIDTarget can only be domestic company
	    	// if ($relationship['relationship'] == 'stockholder') {
	    	// 	if ($relationship['contact_target_entity_type'] != 'domestic') {
	    	// 		$returnData['fix'][$relationship['key']] = 'You can only be a stockholder in a domestic entity.';
	    	// 		continue;
	    	// 	}
	    	// }

		    if (!empty($relationship['id'])) {
		        $entity = $relationshipsTable->find()
		        ->where(['id' => $relationship['id']])->first();
		    } else {
		        $entity = $relationshipsTable->newEntity();
		    }

		    // Since we don't have the contact id of new contacts we get the next contact id to save the relationships
		    $nextInsertId = $relationshipsTable->Contacts->find()->last()->id + 1;

		    $entity->contact_id = (empty($relationship['contact_id'])) ? $nextInsertId : $relationship['contact_id'];
		    $entity->relationship = $relationship['relationship'];
		    $entity->contact_id_target = (!empty($relationship['contact_id_target'])) ? $relationship['contact_id_target'] : $nextInsertId;

		    if ($entity) {
		        $relationshipsTable->save($entity);
		    }
		}

		if (!empty($returnData['fix'])) $returnData['errors'] = true;
		$this->set(compact('returnData'));
	}

	public function extensions($ext)
	{
		$teamMembers = TableRegistry::getTableLocator()->get('TeamMembers');
		$this->viewBuilder()->setLayout('ajax');
		$data = ['phone_number' => null];
		$ext = ltrim($ext, '3');

		$teamMember = $teamMembers->find()
		->where(['id' => $ext])->first();

		if (!$teamMember) {
			echo json_encode($data);
			return;
		}

		$data['phone_number'] = $teamMember->phone_number;
		echo json_encode($data);
	}


    /**
     * @return \Cake\Http\Response|null
     *
     * Webhook catch all gmail notifications. When email came to in box notification send to this route.
     */

    public function gmailNotifications()
    {
        //max_execution_time=90
        $parseDocEmailService = new ParseDocketEmailService();
        $parseDocEmailService->requestHandler($this->request->getData());

        return $this->response->withStatus(200);
    }


    /**
     * @return \Cake\Http\Response|null
     *
     * Get list dockets from db.
     */

    public function getDockets()
    {
        $this->response      = $this->response->withType('json');
        $docketService       = new DocketService();
        $result              = $docketService->getDocketsReport($this->request->getData()); //$this->request->getData()
        $json                = json_encode($result);
        return $this->response->withStringBody($json);
    }


    /**
     * @return \Cake\Http\Response|null
     *
     * Get remote docket_entries from Pacer service.
     */

    public function fetchNewDoc() {
        $this->response = $this->response->withType('json');
	    $request        = $this->request->getData();
        //validation request data
        if ($request['courtType'] !== 'appellate') {
            if (!is_numeric($request['documents_numbered_from_']) && $request['documents_numbered_from_'] !== '') {
                $json = json_encode(['error' => 'Incorrect request data.']);
                return $this->response->withStringBody($json);
            }
            if (!is_numeric($request['documents_numbered_to_']) && $request['documents_numbered_to_'] !== '') {
                $json = json_encode(['error' => 'Incorrect request data.']);
                return $this->response->withStringBody($json);
            }
        } else {
            if (!is_string($request['documents_date_from_']) && $request['documents_date_from_'] !== '') {
                $json = json_encode(['error' => 'Incorrect request data.']);
                return $this->response->withStringBody($json);
            }
            if (!is_string($request['documents_date_to_']) && $request['documents_date_to_'] !== '') {
                $json = json_encode(['error' => 'Incorrect request data.']);
                return $this->response->withStringBody($json);
            }
        }

        $docketService       = new DocketService();
        $result              = $docketService->getRemoteDocketsReport($request);
        $json                = json_encode($result);
        return  $this->response->withStringBody($json);
    }

    /**
     * @return \Cake\Http\Response|null
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function fetchDocketCourt()
    {
        $this->response = $this->response->withType('json');
        $request        = $this->request->getData();

        $courtsTable = TableRegistry::getTableLocator()->get('Courts');
        $courts = $courtsTable->find()->where(['id' => $request['court_id']])->first();

        $request['court_fed_abbr'] = $courts['fed_abbr'];
        $request['court_type'] = $courts['type'];

        if (!is_string($request['case_number']) && $request['case_number'] !== '') {
            $json = json_encode(['error' => 'Incorrect request data.']);
            return  $this->response->withStringBody($json);
        }

        if (!is_string($request['court_id']) && $request['court_id'] !== '') {
            $json = json_encode(['error' => 'Incorrect request data.']);
            return  $this->response->withStringBody($json);
        }

        if (!is_string($request['court_fed_abbr']) && $request['court_fed_abbr'] !== '') {
            $json = json_encode(['error' => 'Incorrect request data.']);
            return  $this->response->withStringBody($json);
        }

        if (!is_string($request['court_type']) && $request['court_type'] !== '') {
            $json = json_encode(['error' => 'Incorrect request data.']);
            return  $this->response->withStringBody($json);
        }

        $docketCourtService  = new ParseDocketCourtsService();
        $result              = $docketCourtService->getRemoteDocketsReport($request);
        $json                = json_encode($result);
        return  $this->response->withStringBody($json);
    }


    /**
     * @return \Cake\Http\Response|null
     *
     * Get remote attachment if local attachment doesn't exist.
     */

    public function fetchAttachmentDoc() {
        $this->response = $this->response->withType('json');
        $request        = $this->request->getData();

        $docketService       = new DocketService();
        $result              = $docketService->getRemoteAttachment($request);
        $json                = json_encode($result);
        return  $this->response->withStringBody($json);
    }

}
