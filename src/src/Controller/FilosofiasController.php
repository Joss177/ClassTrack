<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Filosofias Controller
 *
 * @property \App\Model\Table\FilosofiasTable $Filosofias
 *
 * @method \App\Model\Entity\Filosofia[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilosofiasController extends AppController
{
  public function getList(){
    $this->autoRender = false;
    $lista = $this->Filosofias->find('all',['fields'=>['Filosofias.mision','Filosofias.vision','Filosofias.valores']]);
    return $this->response->withType('application/json')
    ->withStringBody(json_encode($lista));
  }
}
