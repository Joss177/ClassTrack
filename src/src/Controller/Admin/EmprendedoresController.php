<?php
namespace App\Controller\Admin;

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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $emprendedores = $this->paginate($this->Emprendedores);

        $this->set(compact('emprendedores'));
    }

    /**
     * View method
     *
     * @param string|null $id Emprendedore id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $emprendedore = $this->Emprendedores->get($id, [
            'contain' => [],
        ]);

        $this->set('emprendedore', $emprendedore);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
     public function add(){

     $addEmprendedor = $this->Emprendedores->newEntity($this->request->getData(),['validate'=>false]);
     if($this->request->is('post') && !empty($this->request->getData())){
       $post = $this->Emprendedores->patchEntity($addEmprendedor, $this->request->getData(),['validate'=>'update']);
         if($addEmprendedor['img']['error']==0){
           if ($this->Emprendedores->save($addEmprendedor)) {
             $this->Flash->set('Emprendedor agregado con éxito',['key'=>'message_emprendedores', 'element'=>'success']);
             return $this->redirect(['action' => 'index']);
           }else{
              $this->Flash->set('No se puede guardar el emprendedor',['key'=>'message_emprendedores', 'element'=>'error']);
              return $this->redirect(['action' => 'index']);
            }
         }else{
             $this->Flash->set('Es necesario incluir una imagen en el registro,intente de nuevo',['key'=>'message_emprendedores','element'=>'error']);
         }
     }
     $this->set('addEmprendedor',$addEmprendedor);
   }#add
    /**
     * Edit method
     *
     * @param string|null $id Emprendedore id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function edit($id=null){

       if(!empty($id)){
         $emprendedor = $this->Emprendedores->get($id);
         if ($this->request->is(['post', 'put'])) {
           $this->Emprendedores->patchEntity($emprendedor, $this->request->getData(), ['validate'=>'update']);
           if(!empty($emprendedor['img']['name'])){
             $fileName = str_replace(' ','_',$emprendedor['img']['name']);

             $emprendedor['img']['name'] = $fileName;
             $emprendedor2 = $this->Emprendedores->get($id);
             if($emprendedor2['img'] != ""){
               unlink(WWW_ROOT."files/Emprendedores/img/".$emprendedor2['img']);
             }
           }else{
             $emprendedor['file_name'] = $this->request->getData('file_name');

           }

           if ($this->Emprendedores->save($emprendedor)) {
             $this->Flash->set('Emprendedor actualizado con exito',['key'=>'message_emprendedores', 'element'=>'success']);
             return $this->redirect(['action' => 'index']);
           }
           $this->Flash->set('No se puede editar el registro',['key'=>'message_emprendedores','element'=>'error']);
         }

         $this->set('editEmprendedor', $emprendedor);
       }else{
         $this->Flash->set('El id es incorrecto',['key'=>'message_emprendedores','element'=>'error']);
         return $this->redirect(['action' => 'index']);
       }

     }#edit

    /**
     * Delete method
     *
     * @param string|null $id Emprendedore id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $emprendedore = $this->Emprendedores->get($id);
        if ($this->Emprendedores->delete($emprendedore)) {
          $this->Flash->set('Registro eliminado con exito',['key'=>'message_emprendedores', 'element'=>'success']);
        } else {
          $this->Flash->set('No se puede editar el registro,intente de nuevo',['key'=>'message_emprendedores','element'=>'error']);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function publish($id){

      $this->autoRender=false;

      $this->request->allowMethod(['post', 'put']);
      if($this->request->is(['post', 'put'])){
        $emprendedor = $this->Emprendedores->get($id);
        $emprendedor['status'] = ($emprendedor['status'] == 0) ? 1 : 0;
        if ($this->Emprendedores->save($emprendedor)) {
          $this->Flash->set('Emprendedor actualizado con exito',['key'=>'message_emprendedores', 'element'=>'success']);
        }
      }else{
        $this->Flash->set('No se puede actualizar el status',['key'=>'message_emprendedores','element'=>'error']);
      }
      return $this->redirect(['action' => 'index']);

    }#publish
}
