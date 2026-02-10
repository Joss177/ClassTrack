<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Banners Controller
 *
 * @property \App\Model\Table\BannersTable $Banners
 *
 * @method \App\Model\Entity\Banner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BannersController extends AppController
{
  public function getList(){
    $this->autoRender = false;
    $lista = $this->Banners->find('all',['fields'=>['Banners.img','Banners.tittle'],'conditions'=>['Banners.status'=>1]]);
    return $this->response->withType('application/json')
    ->withStringBody(json_encode($lista));
  }
}
