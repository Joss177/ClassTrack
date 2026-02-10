<?php
namespace App\Controller\Admin;

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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $galerias = $this->paginate($this->Galerias);

        $this->set(compact('galerias'));
    }

    /**
     * View method
     *
     * @param string|null $id Galeria id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $galeria = $this->Galerias->get($id, [
            'contain' => [],
        ]);

        $this->set('galeria', $galeria);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
     public function add(){

     $addGaleria = $this->Galerias->newEntity($this->request->getData(),['validate'=>false]);

     if($this->request->is('post') && !empty($this->request->getData())){
       $post = $this->Galerias->patchEntity($addGaleria, $this->request->getData(),['validate'=>'update']);
         if($addGaleria['img']['error']==0){
           if ($this->Galerias->save($addGaleria)) {
             $this->Flash->set('Registro agregado con éxito',['key'=>'message_galerias', 'element'=>'success']);
             return $this->redirect(['action' => 'index']);
           }else{
              $this->Flash->set('No se puede guardar el registro',['key'=>'message_galerias', 'element'=>'error']);
              return $this->redirect(['action' => 'index']);
            }
         }else{
             $this->Flash->set('Es necesario incluir una imagen en el registro,intente de nuevo',['key'=>'message_galerias','element'=>'error']);
         }
     }
     $this->set('addGaleria',$addGaleria);
   }#add

    /**
     * Edit method
     *
     * @param string|null $id Galeria id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function edit($id=null){

       if(!empty($id)){
         $galeria = $this->Galerias->get($id);
         if ($this->request->is(['post', 'put'])) {
           $this->Galerias->patchEntity($galeria, $this->request->getData(), ['validate'=>'update']);

           if(!empty($galeria['img']['name'])){
             $fileName = str_replace(' ','_',$galeria['img']['name']);
             $galeria['img']['name'] = $fileName;
             $galeria2 = $this->Galerias->get($id);
             if($galeria2['img'] != ""){
               unlink(WWW_ROOT."files/Galerias/img/".$galeria2['img']);
             }
           }else{
             $galeria['file_name'] = $this->request->getData('file_name');

           }

           if ($this->Galerias->save($galeria)) {
             $this->Flash->set('Registro actualizado con exito',['key'=>'message_galerias', 'element'=>'success']);
             return $this->redirect(['action' => 'index']);
           }
           $this->Flash->set('No se puede editar el registro',['key'=>'message_galerias','element'=>'error']);
         }

         $this->set('editGaleria', $galeria);
       }else{
         $this->Flash->set('El id es incorrecto',['key'=>'message_galerias','element'=>'error']);
         return $this->redirect(['action' => 'index']);
       }

     }#edit


    /**
     * Delete method
     *
     * @param string|null $id Galeria id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $galeria = $this->Galerias->get($id);
        if ($this->Galerias->delete($galeria)) {
            $this->Flash->set('Registro actualizado con exito',['key'=>'message_galerias', 'element'=>'success']);
        } else {
          $this->Flash->set('No se puede editar el registro',['key'=>'message_galerias','element'=>'error']);
        }

        return $this->redirect(['action' => 'index']);
    }


    public function publish($id){

      $this->autoRender=false;

      $this->request->allowMethod(['post', 'put']);
      if($this->request->is(['post', 'put'])){
        $galeria = $this->Galerias->get($id);
        $galeria['status'] = ($galeria['status'] == 0) ? 1 : 0;
        if ($this->Galerias->save($galeria)) {
          $this->Flash->set('Registro actualizado con exito',['key'=>'message_galerias', 'element'=>'success']);
        }
      }else{
        $this->Flash->set('No se puede actualizar el status',['key'=>'message_galerias','element'=>'error']);
      }
      return $this->redirect(['action' => 'index']);

    }#publish
}
