<?php
namespace App\Controller;

use App\Service\MatterDocketService;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class MattersController extends AppController
{

    public $uses = array('Matters', 'Documents');

	public function index() {
		$search = $this->request->getQuery();
		$this->paginate = [
			'limit' => 100,
			'maxLimit' => 100
		];

		$matters = $this->Matters->find();
		if (!empty($search) && $search['name']) {
			$matters->where(['name LIKE' => '%'.$search['name'].'%']);
		}

		if (!empty($search) && $search['status']) {
			$matters->where(['status' => $search['status']]);
		}

		$matters->order(['Matters.id' => 'ASC']);

		// get thread group stats
		$query = $this->Matters->Threads->find();
		$query->select([
			//'old_matter_id',
			'thread_count' => $query->func()->count('*'),
			'user_count' => $query->func()->COUNT('DISTINCT imported_user_id'),
			'msg_count' => $query->func()->sum('imported_msg_rcvd_count'),
			'last_message' => $query->func()->max('last_message_received')
		])
		->group(['matter_id']);

        $this->paginate($matters);

		$tgStats = [];
		foreach ($query as $stat) {
			if ($stat->matter_id === null) continue;
			$tgStats[$stat->matter_id] = $stat;
		}

		$this->set(compact('matters', 'tgStats', 'search'));
	}

	public function edit($id) {
		$matter = $this->Matters->find()
		->where(['Matters.id' => $id])
		->contain(['MatterCourts', 'MatterCourts.Courts'])
		->first();
		$docketsTable = TableRegistry::getTableLocator()->get('Dockets');
		$courtsTable = TableRegistry::getTableLocator()->get('Courts');

		if ($this->request->getData()) {
            $data = $this->request->getData();
            foreach ($data['matter_courts'] as $doc) {
                $fedAbbr = $courtsTable->find()->where(['id' => $doc['court_id']])->first();

                $docket = $docketsTable->newEntity();
                $docket = $docketsTable->patchEntity($docket, $data);

                $docket->case_name = $data['name'];
                $docket->court_id = $doc['court_id'];
                $docket->matter_id = $data['matter_id'];
                $caseNum = explode('-', $doc['case_number']);
                if (isset($caseNum[3])) {
                    $docket->fed_case_number_judges = '-' . $caseNum[3];
                }
                if (isset($caseNum[4])) {
                    $docket->fed_case_number_judges = '-' . $caseNum[3] . '-' . $caseNum[4];
                }
                $docket->case_number = $caseNum[0] . '-' . $caseNum[1] . '-' . $caseNum[2];
                $docket->court_fed_abbr = $fedAbbr['fed_abbr'];

                $docketsTable->save($docket);
            }
            $this->Matters->patchEntity($matter, $this->request->getData());
			$this->Matters->save($matter);
			return $this->redirect(['action' => 'view', 'id' => $id]);
		}

		$courts = $this->Matters->MatterCourts->Courts->find();
        $courtList = $this->Matters->MatterCourts->Courts->find('list');
		$this->set(compact('matter', 'courts', 'courtList'));
	}

	// 'ResponsibleAttorneys', 'ResponsibleAttorneys.TeamMembers', 'ResponsibleParalegals', 'ResponsibleParalegals.TeamMembers'
	public function view($id) {
		// 'Threads' => ['sort' => ['ImportedUsers.name_lastName', 'ImportedUsers.name_firstName']],
		$matter = $this->Matters->find()
		->where(['Matters.id' => $id])
		->contain(['Threads.ImportedUsers', 'matterNotes', 'matterNotes.TeamMembers', 'MatterCourts', 'MatterCourts.Courts'])
		->first();

		$this->set('matter', $matter);
		$this->addToHistory('Matter', $matter->id);
	}

	public function report($id) {
		$matter = $this->Matters->find()
		->where(['Matters.id' => $id])
		->contain(['Threads' => ['sort' => ['ImportedUsers.name_lastName', 'ImportedUsers.name_firstName']], 'Threads.ImportedMessages', 'Threads.ImportedUsers'])
		->first();

		$groupedData = [];

		foreach ($matter->threads as $thread) {
			$userId = $thread->imported_user_id;
			if (!isset($groupedData[$userId])) {
				$groupedData[$userId] = [
					'user' => $thread->imported_user,
					'threads' => []
				];
			}
			$groupedData[$userId]['threads'][] = $thread;
		}

		$this->set('matter', $matter);
		$this->set('groupedData', $groupedData);
	}

	public function add() {
		if ($this->request->getData()) {
			$matter = $this->Matters->newEntity($this->request->getData());
			if ($this->Matters->save($matter)) {
				return $this->redirect(['action' => 'index']);
			}
		} else {
			$matter = $this->Matters->newEntity();
		}
		$this->set('matter', $matter);
	}


	public function contacts($id) {
		$matter = $this->Matters->find()
		->where(['Matters.id' => $id])
		->contain(['MatterContacts', 'MatterContacts.TeamMembers', 'MatterContacts.ImportedUsers', 'MatterContacts.Contacts', 'MatterContacts.Contacts.primaryAddresses', 'MatterContacts.Contacts.primaryEmails', 'MatterContacts.Contacts.primaryPhones'])
		->first();

		if ($this->request->getData()) {
			$matterContact = $this->Matters->MatterContacts->newEntity($this->request->getData());
			if ($this->Matters->MatterContacts->save($matterContact)) {
				$this->redirect(['action' => 'contacts', 'id' => $id]);
			}
		}

		$this->set('matter', $matter);
	}

	public function contactDelete()
	{
		$this->viewBuilder()->setLayout('ajax');

		$id = $this->request->getquery('id');
		if (empty($id)) {
			exit(false);
		}

		$matterContact = $this->Matters->MatterContacts->get($id);
		$matterContact->is_deleted = 1;
		$this->Matters->MatterContacts->save($matterContact);
	}

    public function documents($mat_id)
    {

        $matter = $this->Matters->findById($mat_id)->first();
        $documentsQuery = $this->Matters->Documents->find()->where(['matter_id' => $mat_id]);

        $paginationOptions = [
            'limit' => 10,
            'order' => [
                'Documents.id' => 'DESC'
            ]
        ];

        $this->set('documents', $this->paginate($documentsQuery, $paginationOptions));
        $this->set('matter', $matter);

    }

    public function docket($matID)
    {
    	//$matterDocketService = new MatterDocketService();

        $matterTable = TableRegistry::getTableLocator()->get('Matters');
        $matter = $matterTable->findById($matID)->contain(['MatterCourts', 'MatterCourts.Courts'])->first();

        $docketTable = TableRegistry::getTableLocator()->get('Dockets');
        $dockets = $docketTable->find()->where(function (QueryExpression $exp, Query $q) use ($matID) {
            return $exp
                ->eq('matter_id', $matID);

        })->contain(['DocketEntries', 'DocketAttachments', 'Courts'])->toList();

    	$this->set('matter', $matter);
        $this->set('dockets', $dockets);
    }
}
