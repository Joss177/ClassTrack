<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Groups');

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['login']);
    }

    /* =========================
     * LISTADO
     * ========================= */
    public function index()
    {
        $conditions = [];

        // Si no es admin, no puede ver admins
        if ($this->Auth->user('group_id') != 1) {
            $conditions['Users.group_id >'] = 1;
        }

        $this->paginate = [
            'limit' => 10,
            'conditions' => $conditions,
            'order' => [
                'Users.created' => 'DESC'
            ]
        ];

        $users = $this->paginate($this->Users);
        $this->set(compact('users'));
    }

    /* =========================
     * LOGIN
     * ========================= */
    public function login()
{
    $this->viewBuilder()->disableAutoLayout();

    // Si ya está autenticado, redirigir a admin
    if ($this->Auth->user()) {
        return $this->redirect([
            'controller' => 'Admin',
            'action' => 'index',
            'prefix' => 'admin'
        ]);
    }

    if ($this->request->is('post')) {

        $user = $this->Auth->identify();

        if ($user) {

            $this->Auth->setUser($user);

            return $this->redirect($this->Auth->redirectUrl([
                'controller' => 'Admin',
                'action' => 'index',
                'prefix' => 'admin'
            ]));
        }

        $this->Flash->error('Correo o contraseña incorrectos');
    }
}


    /* =========================
     * CREAR USUARIO
     * ========================= */
    public function signup()
    {
        $this->viewBuilder()->disableAutoLayout();
        $this->autoRender = true;

        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $data['group_id'] = 1;

            // Validación manual de confirmar contraseña
            if ($data['password'] !== $data['confirm_password']) {

                $this->Flash->error('Las contraseñas no coinciden.');
                $this->set(compact('user'));
                return;
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {

                $this->Flash->success('Usuario creado correctamente.');

                return $this->redirect(['action' => 'login']);
            } else {

                // Si hay errores de validación (ej: correo duplicado)
                if ($user->getErrors()) {
                    $this->Flash->error('Verifica los datos ingresados.');
                } else {
                    $this->Flash->error('Error al crear el usuario.');
                }
            }
        }

        $this->set(compact('user'));
    }

    /* =========================
     * EDITAR USUARIO
     * ========================= */
    public function edit($id)
{
    $editUser = $this->Users->get($id);

    if ($this->request->is(['post','put'])) {
        $this->Users->patchEntity($editUser, $this->request->getData());

        if ($this->Users->save($editUser)) {
            $this->Flash->success('Usuario actualizado correctamente');
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error('No se pudo actualizar el usuario');
    }

    $groups = $this->Users->Groups->find('list');
    $this->set(compact('editUser','groups'));
}


    /* =========================
     * CAMBIAR PASSWORD
     * ========================= */
    public function editPass($id)
    {
        $editUser = $this->Users->get($id);

        if ($this->request->is(['post', 'put'])) {

            $data = $this->request->getData();

            // Validación simple
            if (empty($data['password'])) {
                $this->Flash->error('La contraseña no puede ir vacía');
                return;
            }

            // Eliminar campo que no existe en la entidad
            unset($data['confirm_pass']);

            $this->Users->patchEntity($editUser, $data);

            if ($this->Users->save($editUser)) {
                $this->Flash->success('Contraseña actualizada correctamente');
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('No se pudo actualizar la contraseña');
        }

        $this->set(compact('editUser'));
    }


    /* =========================
     * ELIMINAR
     * ========================= */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success('Usuario eliminado con éxito');
        } else {
            $this->Flash->error('No se pudo eliminar el usuario');
        }

        return $this->redirect(['action' => 'index']);
    }

    /* =========================
     * LOGOUT
     * ========================= */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
