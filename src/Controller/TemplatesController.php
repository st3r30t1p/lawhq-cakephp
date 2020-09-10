<?php

namespace App\Controller;

use App\Model\Table\TemplatesTable;
use App\Service\GoogleService;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Class TemplatesController
 * @package App\Controller
 */
class TemplatesController extends AppController {

    public function index() {
        $templates = $this->paginate($this->Templates);

        $this->set('templates', $templates);
    }

    /**
     * @param null $id
     */
    public function edit($id = null) {

        $this->loadModel('SectionTemplates');
        $template = $this->Templates->findById($id)->first();

        $sTemplates = $this->SectionTemplates->find('all');

        $sectionTemplates = [];

        foreach($sTemplates as $sTemplate) {
            $sectionTemplates[$sTemplate['id']] = $sTemplate['name'];
        }

        $this->set(compact('template', 'sectionTemplates'));
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function update($id = null)
    {
        $request = $this->request->getData();
        $paramsData = [];

//        foreach ($request['parameters'] as $parameter) {
//            $paramsData[$parameter['name']] = $parameter['type'];
//        }
//
//        $request['parameters'] = json_encode($paramsData);

        $templatesTable = TableRegistry::getTableLocator()->get('Templates');
        $template = $templatesTable->get($id);
        $templatesTable->patchEntity($template, $request);

        $saved = $this->Templates->save($template);

        if ($saved) {

            $unlinkSections = $templatesTable->SectionTemplates->find()->toList();

            if (!empty($unlinkSections)) {
                $templatesTable->SectionTemplates->unlink($template, $unlinkSections);
            }

            $linkSections = $templatesTable->SectionTemplates->find()->where(['id IN' => $request['sectionTemplates']])->toList();

            if (!empty($linkSections)) {
                $templatesTable->SectionTemplates->link($template, $linkSections);
            }

            $this->Flash->success(__('The template has been saved.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function add() {
        $this->loadModel('SectionTemplates');

        $request = $this->request->getData();
        $sTemplates = $this->SectionTemplates->find('all');

        $sectionTemplates = [];

        foreach($sTemplates as $sTemplate) {
            $sectionTemplates[$sTemplate['id']] = $sTemplate['name'];
        }

        if ($request) {
//            $paramsData = [];
//
//            foreach ($request['parameters'] as $parameter) {
//
//                $paramsData[$parameter['name']] = $parameter['type'];
//
//            }
//
//            $request['parameters'] = json_encode($paramsData);

            $template = $this->Templates->newEntity($request);

            $saved = $this->Templates->save($template);

            if ($saved) {

                $templateTable = TableRegistry::getTableLocator()->get('Templates');
                $template = $templateTable->get($saved->id);

                foreach($request['sectionTemplates'] as $sTemplateId) {
                    $sectionTemplate = $templateTable->SectionTemplates->findById($sTemplateId)->first();
                    $templateTable->SectionTemplates->link($template, [$sectionTemplate]);
                }

                $this->Flash->success(__('The template has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The template could not be saved. Please, try again.'));

        } else {
            $template = $this->Templates->newEntity();
        }

        $this->set(compact('template', 'sectionTemplates'));
    }

    /**
     * @param $id
     * @return \Cake\Http\Response|null
     */
    public function delete($id)
    {
        $template = $this->Templates->get($id);

        if ($this->Templates->delete($template)) {
            $this->Flash->success(__('The template has been deleted.'));
        } else {
            $this->Flash->error(__('The template could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);;
    }

}
