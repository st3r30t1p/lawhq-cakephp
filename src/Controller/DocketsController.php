<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Service\Docket\ParseDocketCourtsService;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\Query;
use GuzzleHttp\Client;

/**
 * Dockets Controller
 *
 * @property \App\Model\Table\DocketsTable $Dockets
 *
 * @method \App\Model\Entity\Docket[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DocketsController extends AppController
{

    /**
     * @var ParseDocketCourtsService
     */
    private $parseDocketCourts;

    public function __construct(ServerRequest $request = null, Response $response = null, $name = null, $eventManager = null, $components = null)
    {
        parent::__construct($request, $response, $name, $eventManager, $components);
        $this->parseDocketCourts = new ParseDocketCourtsService();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Courts', 'Matters', 'DocketEntries', 'DocketAttachments']
        ];

        $dockets = $this->Dockets->find();

        if ($this->request->getQuery('q')) {
            $query = $this->request->getQuery('q');
            $dockets->where(function (QueryExpression $exp, Query $q) use ($query) {
                return $exp
                    ->addCase([
                        $q->newExpr()->like('case_number', '%'.$query.'%'),
                        $q->newExpr()->like('case_name', '%'.$query.'%'),
                        $q->newExpr()->like('fed_case_number_judges', '%'.$query.'%'),
                        $q->newExpr()->like('Courts.name', '%'.$query.'%')
                    ]);
            });
        }


        $dockets = $this->paginate($dockets);

        $this->set(compact('dockets'));
    }

    /**
     * View method
     *
     * @param string|null $id Docket id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $docket = $this->Dockets->get($id, [
            'contain' => ['Courts', 'Matters', 'DocketAttachments', 'DocketEntries']
        ]);

        $court = $this->Dockets->Courts->find()->where(['id' => $docket->court_id])->first();

        $this->set(compact('docket', 'court'));
        $this->addToHistory('Docket', $docket->id);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $docket = $this->Dockets->newEntity();
        if ($this->request->is('post')) {
            $docket = $this->Dockets->patchEntity($docket, $this->request->getData());
            if ($this->Dockets->save($docket)) {
                $lastDocket = $this->Dockets->find('list', ['limit' => 1])->orderDesc('id');

                foreach ($lastDocket as $lastId) {
                    return $this->redirect(['action' => "view/{$lastId}"]);
                }
            }
            $this->Flash->error(__('The docket could not be saved. Please, try again.'));
        }

        $courts = $this->Dockets->Courts->find();
//        $cast = $courts->newExpr('COALESCE(NULLIF(CAST(name as UNSIGNED), 0), 99999), name');
        $courts->select(['id','name', 'type', 'fed_abbr'])
            ->where(['`system`' => 'federal'])
            ->order('sort_order');

        $this->set(compact('docket', 'courts'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Docket id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $docket = $this->Dockets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $docket = $this->Dockets->patchEntity($docket, $this->request->getData());
            if ($this->Dockets->save($docket)) {
                $this->Flash->success(__('The docket has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The docket could not be saved. Please, try again.'));
        }
        $courts = $this->Dockets->Courts->find('list', ['limit' => 200]);
        $matters = $this->Dockets->Matters->find('list', ['limit' => 200]);
        $this->set(compact('docket', 'courts', 'matters'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Docket id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $docket = $this->Dockets->get($id);
        if ($this->Dockets->delete($docket)) {
            $this->Flash->success(__('The docket has been deleted.'));
        } else {
            $this->Flash->error(__('The docket could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
