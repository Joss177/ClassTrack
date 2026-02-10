<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Comunidades Controller
 *
 * @property \App\Model\Table\ComunidadesTable $Comunidades
 *
 * @method \App\Model\Entity\Comunidade[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ComunidadesController extends AppController
{
  public function getList(){
    $this->autoRender = false;
    $lista = $this->Comunidades->find('all',['fields'=>['Comunidades.img','Comunidades.tittle','Comunidades.description']]);
    return $this->response->withType('application/json')
    ->withStringBody(json_encode($lista));
  }
}
