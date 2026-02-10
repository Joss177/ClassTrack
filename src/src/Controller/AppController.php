<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        if ($this->request->getParam('prefix') === 'admin') {
            $this->loadComponent('Auth', [
                'loginAction' => [
                    'controller' => 'Users',
                    'action' => 'login',
                    'prefix' => 'admin'
                ],
                'loginRedirect' => [
                    'controller' => 'Admin',
                    'action' => 'index',
                    'prefix' => 'admin'
                ],
                'logoutRedirect' => [
                    'controller' => 'Users',
                    'action' => 'login',
                    'prefix' => 'admin'
                ],
                'authError' => false,
                'authenticate' => [
                    'Form' => [
                        'fields' => [
                            'username' => 'correo',
                            'password' => 'password'
                        ]
                    ]
                ],
                'storage' => [
                    'className' => 'Session',
                    'key' => 'Auth.Admin'
                ]
            ]);
        }
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        if ($this->request->getParam('prefix') === 'admin') {
            $this->viewBuilder()->setLayout('admin');
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if ($this->request->getParam('prefix') === 'admin') {
            $this->Auth->allow();
        }
    }


    public function isAuthorized($user = null)
    {
        if (!$this->request->getParam('prefix')) {
            return true;
        }

        if ($this->request->getParam('prefix') === 'admin') {
            return isset($user['group_id']) && $user['group_id'] == 1;
        }

        return false;
    }
}
