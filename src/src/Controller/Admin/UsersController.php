<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\I18n\FrozenTime;


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
        $this->Auth->allow([
            'login',
            'recuperarPassword',
            'editPass'
        ]);
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

        if ($this->Auth->user()) {
            return $this->redirect([
                'controller' => 'Admin',
                'action' => 'index',
                'prefix' => 'admin'
            ]);
        }

        if ($this->request->is('post')) {

            $correo = $this->request->getData('correo');
            $password = $this->request->getData('password');

            if (empty($correo) || empty($password)) {
                $this->Flash->error('Llenar todos los campos');
                return;
            }

            $usuario = $this->Users->find()
                ->where(['correo' => $correo])
                ->first();

            if (!$usuario) {
                $this->Flash->error('Usuario no encontrado');
                return;
            }

            $user = $this->Auth->identify();

            if ($user) {

                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl([
                    'controller' => 'Admin',
                    'action' => 'index',
                    'prefix' => 'admin'
                ]));
            }

            $this->Flash->error('Contraseña o Correo Incorrectas');
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


    public function edit($id = null)
    {
        $users = $this->Users->find('all');

        if ($this->request->is(['post', 'put'])) {

            $user = $this->Users->get($this->request->getData('id'));

            $this->Users->patchEntity($user, $this->request->getData(), [
                'fields' => ['nombre_completo', 'correo']
            ]);

            if ($this->Users->save($user)) {
                $this->Flash->success('Usuario actualizado.');
            } else {
                $this->Flash->error('Error al actualizar.');
            }

            return $this->redirect(['action' => 'edit']);
        }

        $this->set(compact('users'));
    }


    /* =========================
     * CAMBIAR PASSWORD
     * ========================= */
    public function editPass($token = null)
    {
        $this->viewBuilder()->disableAutoLayout();

        // Validar token vacío
        if (!$token) {
            $this->Flash->error('Token inválido');
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }

        // Buscar usuario por token válido y no expirado
        $user = $this->Users->find()
            ->where([
                'token' => $token,
                'token_expira >=' => FrozenTime::now()
            ])
            ->first();

        // Token inválido o expirado
        if (!$user) {
            $this->Flash->error('El enlace es inválido o ha expirado');
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }

        // Procesar formulario
        if ($this->request->is(['post', 'put'])) {

            $password = $this->request->getData('password');
            $confirm  = $this->request->getData('confirm_password');

            // Validaciones básicas
            if (empty($password) || empty($confirm)) {
                $this->Flash->error('Todos los campos son obligatorios');
                return;
            }

            if ($password !== $confirm) {
                $this->Flash->error('Las contraseñas no coinciden');
                return;
            }

            if (strlen($password) < 6) {
                $this->Flash->error('La contraseña debe tener mínimo 6 caracteres');
                return;
            }

            // Asignar nueva contraseña (se hashea automáticamente en Entity)
            $user->password = $password;

            // Limpiar token
            $user->token = null;
            $user->token_expira = null;

            // Guardar sin reglas (para evitar conflictos como unique, etc.)
            if ($this->Users->save($user, ['checkRules' => false])) {

                $this->Flash->success('Contraseña actualizada correctamente');

                return $this->redirect([
                    'controller' => 'Users',
                    'action' => 'login'
                ]);
            }

            $this->Flash->error('Error al actualizar la contraseña');
        }

        $this->set(compact('user'));
    }


    /* =========================
     * ELIMINAR
     * ========================= */
    public function delete($id = null)
    {
        // Solo permitir método POST o DELETE (seguridad)
        $this->request->allowMethod(['post', 'delete']);

        // Buscar el usuario
        $user = $this->Users->get($id);

        // Intentar eliminar
        if ($this->Users->delete($user)) {
            $this->Flash->success('El usuario ha sido eliminado correctamente.');
        } else {
            $this->Flash->error('No se pudo eliminar el usuario. Inténtalo nuevamente.');
        }

        // Redirigir al listado
        return $this->redirect(['action' => 'edit']); // o 'index' si usas index
    }

    /* =========================
     * LOGOUT
     * ========================= */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }




public function recuperarPassword()
{
    if ($this->request->is('post')) {

        $correo = $this->request->getData('correo');

        $user = $this->Users->find()
            ->where(['correo' => $correo])
            ->first();

        if (!$user) {
            $this->Flash->error('Correo no encontrado');
            return $this->redirect([
                'prefix' => 'admin',
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }


        $token = bin2hex(random_bytes(16));


        $this->Users->query()
            ->update()
            ->set([
                'token' => $token,
                'token_expira' => date('Y-m-d H:i:s', strtotime('+1 hour'))
            ])
            ->where(['id' => $user->id])
            ->execute();

        $link = Router::url([
            'controller' => 'Users',
            'action' => 'editPass',
            $token
        ], true);


        $email = new Email('default');

        $email->setTo($correo)
            ->setSubject('Recuperar contraseña')
            ->setEmailFormat('html')
            ->send(
                '<p>Hola <strong>' . $user->nombre_completo . '</strong>,</p>

                <p>Se solicitó cambiar tu contraseña.</p>

                <p>
                    <a href="' . $link . '" style="
                        display:inline-block;
                        padding:12px 25px;
                        background:#007bff;
                        color:#ffffff;
                        text-decoration:none;
                        border-radius:5px;
                        font-weight:bold;">
                        Recuperar contraseña
                    </a>
                </p>

                <p>Este enlace expira en 1 hora.</p>
            ');

        $this->Flash->success('Revisa tu correo para continuar.');

        return $this->redirect([
            'controller' => 'Users',
            'action' => 'login'
        ]);
    }
}
}
