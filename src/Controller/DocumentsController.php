<?php

namespace App\Controller;

use App\Service\DocumentService;
use Cake\Log\Log;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;
use Google_Exception;

/**
 * Class ContactsController
 * @package App\Controller
 */
class DocumentsController extends AppController
{

    public function add()
    {
        $requestData = $this->request->getData();

        if ($requestData) {

            $query = TableRegistry::getTableLocator()->get('Matters')->find();
            $matter = $query->where(['id' => $requestData['matter_id']])->contain(['MatterContacts', 'MatterContacts.TeamMembers', 'MatterContacts.ImportedUsers', 'MatterContacts.Contacts', 'MatterContacts.Contacts.primaryAddresses', 'MatterContacts.Contacts.primaryEmails', 'MatterContacts.Contacts.primaryPhones'])->first();

            try {

                $DocumentData = DocumentService::getProcessedData($matter, $requestData);

                if (empty($DocumentData)) {
                    $this->Flash->error(__('The document could not be generated. Please, try again.'));
                    $this->redirect($this->referer());
                } else {
                    $document = $this->Documents->newEntity($DocumentData);

                    if ($this->Documents->save($document)) {
                        $this->Flash->success(__('The document has been generated.'));
                        $this->redirect("/matters/documents/{$requestData['matter_id']}");
                    }
                }

            } catch (Google_Exception $e) {
                Log::write('debug', $e->getMessage());
            }

        } else {

            $templateDocId = null;
            $predFields = include (ROOT .'/persistent/google_document_keys.php');

            if (isset($this->request->getParam('?')['template'])) {
                $templateDocId = $this->request->getParam('?')['template'];
            }

            $templates = (new TableLocator)->get('templates')->find('all')->toArray();
            $formTemplates = [];
            $templateObj = false;

            foreach ($templates as $template) {
                $formTemplates[$template->google_doc_id] = $template->name;

                if ($templateDocId === $template->google_doc_id) {
                    $templateObj = $template;
                }
            }

            $document = $this->Documents->newEntity();
            $matter = (new TableLocator)->get('matters')->get($this->request->getParam('?')['mat_id']);

            $this->set('template', $templateObj ?: $templates[0]);
            $this->set(compact('formTemplates', 'predFields', 'matter', 'document'));
        }
    }

    public function index()
    {
        $this->set('documents', $this->Documents->find('all')->order('id DESC'));
    }
}
