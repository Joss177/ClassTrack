<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;


class UsersController extends AppController{

  public function initialize()
  {
      parent::initialize();
      $this->loadModel('Groups');
  }


    public function beforeFilter(Event $event){
      parent::beforeFilter($event);
      $this->Auth->allow('login');
    }

    public function index(){

      $paginate = [
        'limit' => 10,
        'order' => [
          'Users.created' => 'DESC'
        ]
      ];

      $conditions = array();
      if($this->Auth->user('group_id')!=1){
        $conditions[] = ['group_id >'=>1];
      }

      $this->loadComponent('Paginator');
      $users = $this->Paginator->paginate($this->Users->find('all', ['conditions'=> $conditions]),$paginate);
      $this->set('users', $users);

    }

    public function edit($id=null){

      $groups = $this->Groups->find('all',['fields'=>['id','name'], 'conditions'=>['id >'=>1]])
      ->enableHydration(false)
      ->toList();
      $grupos = array();
      foreach ($groups as $key => $g) {
        $grupos[$g['id']] = $g['name'];
      }

      if(!empty($id)){
        $user = $this->Users->get($id);
        if ($this->request->is(['post', 'put'])) {
          $this->Users->patchEntity($user, $this->request->getData(), ['validate'=>'update']);
          if ($this->Users->save($user)) {
            $this->Flash->set('Usuario actualizado con exito',['key'=>'message', 'element'=>'success']);
            return $this->redirect(['action' => 'index']);
          }
          $this->Flash->set('No se puede editar el usuario',['key'=>'message','element'=>'error']);
        }

        $this->set('editUser', $user);
        $this->set('groups',$grupos);

      }else{
        $this->Flash->set('El id es incorrecto',['key'=>'message','element'=>'error']);
        return $this->redirect(['action' => 'index']);
      }

    }#edit

    public function editPass($id=null){

      if(!empty($id)){
        $user = $this->Users->get($id);
        if ($this->request->is(['post', 'put'])) {
          $this->Users->patchEntity($user, $this->request->getData(), ['validate'=>'update']);
          if ($this->Users->save($user)) {
            $this->Flash->set('Usuario actualizado con exito',['key'=>'message', 'element'=>'success']);
            return $this->redirect(['action' => 'index']);
          }
          $this->Flash->set('No se puede editar el usuario',['key'=>'message','element'=>'error']);
        }

        $this->set('editUser', $user);
      }else{
        $this->Flash->set('El id es incorrecto',['key'=>'message','element'=>'error']);
        return $this->redirect(['action' => 'index']);
      }

    }#edit

    public function login(){

      $login = $this->Users->newEntity();
      if($this->request->is('post') && !empty($this->request->getData())){
        $check_login = $this->Users->patchEntity($login, $this->request->getData(),['validate'=>'login']);
        if($check_login->getErrors()){
          $this->Flash->error('Favor de llenar todos los campos');
        }else {
          $user = $this->Auth->identify();
          if ($user) {
            $this->Auth->setUser($user);
            // $this->Flash->success('Hola',['key'=>'message']);
            return $this->redirect($this->Auth->redirectUrl());
          } else {
            $this->Flash->error('Usuario o contraseña incorrectos');
          }
          // return $this->redirect(['controller'=>'Admin','action'=>'index']);
        }
      }
      $this->set('login',$login);


    }#login()

    public function signup(){

      $sign_up = $this->Users->newEntity($this->request->getData(),['validate'=>false]);
      $groups = $this->Groups->find('all',['fields'=>['id','name'], 'conditions'=>['id >'=>1]])
      ->enableHydration(false)
      ->toList();
      $grupos = array();
      foreach ($groups as $key => $g) {
        $grupos[$g['id']] = $g['name'];
      }
      if($this->request->is('post') && !empty($this->request->getData())){

        $post = $this->Users->patchEntity($sign_up, $this->request->getData(),['validate'=>'update']);
        if($sign_up->getErrors()){
          $this->Flash->error('Favor de llenar todos los datos',['key'=>'message']);
        }else{
          if($this->Users->save($sign_up)){
            $this->Flash->success('Usuario agregado correctamente',['key'=>'message']);
            return $this->redirect(['action'=>'index']);
          }
          $this->Flash->error(__('No se puede guardar el usuario'));
        }
      }
      $this->set('groups',$grupos);
      $this->set('sign_up',$sign_up);
    }#signup()

    public function logout(){
      return $this->redirect($this->Auth->logout());
    }#logout()

    public function delete($id){

      $this->request->allowMethod(['post', 'delete']);
      if($this->request->is(['post', 'delete'])){
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
          $this->Flash->set('Usuario eliminado con exito',['key'=>'message', 'element'=>'success']);
          return $this->redirect(['action' => 'index']);
        }
      }
      $this->Flash->set('No se puede eliminar el usuario',['key'=>'message','element'=>'error']);


    }#delete

}
