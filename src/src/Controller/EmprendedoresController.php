<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Emprendedores Controller
 *
 * @property \App\Model\Table\EmprendedoresTable $Emprendedores
 *
 * @method \App\Model\Entity\Emprendedore[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmprendedoresController extends AppController
{
  public function getList(){
    $this->autoRender = false;
    $lista = $this->Emprendedores->find('all',['fields'=>['Emprendedores.img'],'conditions'=>['Emprendedores.status'=>1]]);
    return $this->response->withType('application/json')
    ->withStringBody(json_encode($lista));
  }
}
