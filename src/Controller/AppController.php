<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
        $this->Cookie->configKey('history', 'encryption', false);
        $this->Cookie->setConfig([
            'expires' => '+30 days',
            'httpOnly' => true
        ]);

        $this->viewBuilder()->setLayout('materialize');

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        $this->checkValidUserSession();
        $this->getHistory();
        $this->manageEditing = $this->getRequest()->getSession()->read('manageEditing');
    }

    protected function checkValidUserSession() {
        // allow access to the login page
        if ($this->request->getParam('controller') == 'TeamMembers' && $this->request->getParam('action') == 'login' || $this->request->getParam('action') == 'gmailNotifications' || $this->request->getParam('action') == 'extensions')
            return;

        $userId = $this->getRequest()->getSession()->read('User.id');
        if ($userId === null) {
            $this->getRequest()->getSession()->write('requestedUrl', $this->request->env('REQUEST_URI'));
            $this->response = $this->redirect(['controller' => 'TeamMembers', 'action' => 'login']);
            $this->response->send();
            die();
        }

        $this->loadModel('TeamMembers');
        $user = $this->TeamMembers->findById($userId)->first();

        if (!$user->active) {
            $this->redirect(['controller' => 'TeamMembers', 'action' => 'login']);
        }
        
        // For new team members first time login make sure they set a new password
        if ($this->request->getParam('action') != 'updatePassword' && !$user->password_updated) {
            $this->redirect(['controller' => 'TeamMembers', 'action' => 'updatePassword']);
        }
        $this->appUser = $user;
        $this->set('appUser', $user);
        $this->set('permissionManageUsers', $user->manage_users);
    }

    public function getHistory()
    {
        $this->history = [];
        if ($this->Cookie->read('history')) {
            $this->history = $this->Cookie->read('history');
        }
        $this->set('history', $this->history);
    }

    public function addToHistory($page, $id)
    {
        $history = $this->history;
        $pageInfo = [
            'page_name' => $page,
            'id' => $id,
            'url' => Router::url(null, true)
        ];

        foreach ($history as $key => $h) {
            if ($h['page_name'] == $page && $h['id'] == $id) {
                unset($history[$key]);
            }
        }
        // Only ever let there be 10 items in history
        if (isset($history[10])) {
            unset($history[10]);
        }

        array_unshift($history, $pageInfo);
        $this->Cookie->write('history', $history);
    }
}
