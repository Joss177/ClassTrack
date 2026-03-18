<?php
namespace App\Controller\Admin;
use Cake\Event\Event;
use App\Controller\AppController;

class GestionController extends AppController
{
    public function index()
    {
        return $this->redirect([
            'prefix' => 'admin', // ← minúscula
            'controller' => 'Docentes',
            'action' => 'index'
        ]);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $user = $this->Auth->user();

        if (!$user || $user['group_id'] != 2) {

            $this->Flash->error('No tienes permisos para acceder.');

            return $this->redirect([
                'prefix' => 'Admin',
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }
    }
}
