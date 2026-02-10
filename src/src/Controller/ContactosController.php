<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Contactos Controller
 *
 * @property \App\Model\Table\ContactosTable $Contactos
 *
 * @method \App\Model\Entity\Contacto[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContactosController extends AppController
{

  public function add(){

    $this->autoRender = false;

    $this->request->allowMethod(['post', 'put']);

    $addContacto = $this->Contactos->newEntity($this->request->getData(),['validate'=>false]);
    if($this->request->is('post') && !empty($this->request->getData())){

      $post = $this->Contactos->patchEntity($addContacto, $this->request->getData(),['validate'=>'update']);
      if($addContacto->getErrors()){
        $data = array(
          'fullname' => $addContacto['name'],
          'email' => $addContacto['email'],
          'phone' => $addContacto['phone'],
          'message' => $addContacto['message'],
        );
        return $this->response->withType('application/json')->withStringBody(json_encode('Favor de llenar todos los campos'));
      }else{
        if($this->Contactos->save($addContacto)){

          return $this->response->withType('application/json')->withStringBody(json_encode('true'));
        }
        return $this->response->withType('application/json')->withStringBody(json_encode('No se puede guardar el contacto'));
      }
    }
    return $this->response->withType('application/json')->withStringBody(json_encode('No se puede guardar el contacto'));

  }#add


}
