<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SectionTemplates Controller
 *
 * @property \App\Model\Table\SectionTemplatesTable $SectionTemplates
 *
 * @method \App\Model\Entity\SectionTemplate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SectionTemplatesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $sectionTemplates = $this->paginate($this->SectionTemplates);

        $this->set(compact('sectionTemplates'));
    }

    /**
     * View method
     *
     * @param string|null $id Section Template id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
//    public function view($id = null)
//    {
//        $sectionTemplate = $this->SectionTemplates->get($id, [
//            'contain' => []
//        ]);
//
//        $this->set('sectionTemplate', $sectionTemplate);
//    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sectionTemplate = $this->SectionTemplates->newEntity();
        if ($this->request->is('post')) {
            $sectionTemplate = $this->SectionTemplates->patchEntity($sectionTemplate, $this->request->getData());
            if ($this->SectionTemplates->save($sectionTemplate)) {
                $this->Flash->success(__('The section template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section template could not be saved. Please, try again.'));
        }
        $this->set(compact('sectionTemplate'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Section Template id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sectionTemplate = $this->SectionTemplates->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sectionTemplate = $this->SectionTemplates->patchEntity($sectionTemplate, $this->request->getData());
            if ($this->SectionTemplates->save($sectionTemplate)) {
                $this->Flash->success(__('The section template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section template could not be saved. Please, try again.'));
        }
        $this->set(compact('sectionTemplate'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Section Template id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sectionTemplate = $this->SectionTemplates->get($id);
        if ($this->SectionTemplates->delete($sectionTemplate)) {
            $this->Flash->success(__('The section template has been deleted.'));
        } else {
            $this->Flash->error(__('The section template could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
