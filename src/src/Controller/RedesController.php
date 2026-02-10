<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Redes Controller
 *
 * @property \App\Model\Table\RedesTable $Redes
 *
 * @method \App\Model\Entity\Rede[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RedesController extends AppController
{
  public function getList(){
    $this->autoRender = false;
    $lista = $this->Redes->find('all',['fields'=>['Redes.facebook','Redes.whatsapp','Redes.instagram']]);
    return $this->response->withType('application/json')
    ->withStringBody(json_encode($lista));
  }
}
