<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class ConfiguracionController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->loadModel('Users');
    }

    public function index()
    {
        $userId = $this->Auth->user('id');
        $user = $this->Users->get($userId);

        if ($this->request->is(['post', 'put'])) {
            $action = $this->request->getData('action');

            if ($action === 'update_info') {
                $user = $this->Users->patchEntity($user, $this->request->getData(), [
                    'fields' => ['nombre_completo', 'correo']
                ]);

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Usuario modificado'), ['key' => 'configuracion']);
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Error al guardar'), ['key' => 'configuracion']);
                }

            } elseif ($action === 'update_password') {
                $pass1 = $this->request->getData('password1');
                $pass2 = $this->request->getData('password2');

                if (!$pass1 || !$pass2 || $pass1 !== $pass2) {
                    $this->Flash->error(__('Las contraseñas no coinciden'), ['key' => 'configuracion']);
                    $this->set('openPasswordModal', true);
                } else {
                    $user->password = $pass1;
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('Usuario modificado'), ['key' => 'configuracion']);
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('Error al guardar'), ['key' => 'configuracion']);
                        $this->set('openPasswordModal', true);
                    }
                }
            }
        }

        $this->set(compact('user'));
    }
}
