<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Galerias Controller
 *
 * @property \App\Model\Table\GaleriasTable $Galerias
 *
 * @method \App\Model\Entity\Galeria[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GaleriasController extends AppController
{
  public function getList(){
    $this->autoRender = false;
    $lista = $this->Galerias->find('all',['fields'=>['Galerias.img'],'conditions'=>['Galerias.status'=>1]]);
    return $this->response->withType('application/json')
    ->withStringBody(json_encode($lista));
  }
}
