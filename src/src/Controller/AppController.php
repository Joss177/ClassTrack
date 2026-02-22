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

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'correo',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'loginRedirect' => [
                'controller' => 'Admin',
                'action' => 'index',
                'prefix' => 'admin'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'authError' => false,
            'storage' => [
                'className' => 'Session'
            ]
        ]);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // Permitir acceso sin login a estas acciones
        $this->Auth->allow(['login', 'signup']);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        // Usar layout admin solo cuando el prefix sea admin
        if ($this->request->getParam('prefix') === 'admin') {
            $this->viewBuilder()->setLayout('admin');
        }
    }

    public function isAuthorized($user = null)
    {
        // Si es ruta admin, validar group_id
        if ($this->request->getParam('prefix') === 'admin') {
            return isset($user['group_id']) && $user['group_id'] == 1;
        }

        // Para el resto permitir acceso
        return true;
    }
}
