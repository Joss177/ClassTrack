<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Procesos Controller
 *
 * @property \App\Model\Table\ProcesosTable $Procesos
 *
 * @method \App\Model\Entity\Proceso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProcesosController extends AppController
{
  public function getList(){
    $this->autoRender = false;
    $lista = $this->Procesos->find('all',['fields'=>['Procesos.tittle1','Procesos.description1','Procesos.tittle2','Procesos.description2']]);
    return $this->response->withType('application/json')
    ->withStringBody(json_encode($lista));
  }

}
