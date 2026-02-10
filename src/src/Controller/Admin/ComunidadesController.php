<?php
namespace App\Controller\Admin;

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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $comunidades = $this->paginate($this->Comunidades);

        $this->set(compact('comunidades'));
    }

    /**
     * View method
     *
     * @param string|null $id Comunidade id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comunidade = $this->Comunidades->get($id, [
            'contain' => [],
        ]);

        $this->set('comunidade', $comunidade);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
     public function add(){

     $addComunidad = $this->Comunidades->newEntity($this->request->getData(),['validate'=>false]);
     if($this->request->is('post') && !empty($this->request->getData())){
       $post = $this->Comunidades->patchEntity($addComunidad, $this->request->getData(),['validate'=>'update']);
         if($addComunidad['img']['error']==0){
           if ($this->Comunidades->save($addComunidad)) {
             $this->Flash->set('Comunidad agregado con éxito',['key'=>'message_comunidades', 'element'=>'success']);
             return $this->redirect(['action' => 'index']);
           }else{
              $this->Flash->set('No se puede guardar ela comunidad',['key'=>'message_comunidades', 'element'=>'error']);
              return $this->redirect(['action' => 'index']);
            }
         }else{
             $this->Flash->set('Es necesario incluir una imagen a la comunidad,intente de nuevo',['key'=>'message_comunidades','element'=>'error']);
         }
     }
     $this->set('addComunidad',$addComunidad);
   }#add

    /**
     * Edit method
     *
     * @param string|null $id Comunidade id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

            public function edit($id=null){

              if(!empty($id)){
                $comunidad = $this->Comunidades->get($id);
                if ($this->request->is(['post', 'put'])) {
                  $this->Comunidades->patchEntity($comunidad, $this->request->getData(), ['validate'=>'update']);
                  if(!empty($comunidad['img']['name'])){
                    $fileName = str_replace(' ','_',$comunidad['img']['name']);

                    $comunidad['img']['name'] = $fileName;
                    $comunidad2 = $this->Comunidades->get($id);
                    if($comunidad2['img'] != ""){
                      unlink(WWW_ROOT."files/Comunidades/img/".$comunidad2['img']);
                    }
                  }else{
                    $comunidad['file_name'] = $this->request->getData('file_name');

                  }

                  if ($this->Comunidades->save($comunidad)) {
                    $this->Flash->set('Comunidad actualizado con exito',['key'=>'message_comunidades', 'element'=>'success']);
                    return $this->redirect(['action' => 'index']);
                  }
                  $this->Flash->set('No se puede editar la comunidad',['key'=>'message_comunidades','element'=>'error']);
                }

                $this->set('editComunidad', $comunidad);
              }else{
                $this->Flash->set('El id es incorrecto',['key'=>'message_comunidades','element'=>'error']);
                return $this->redirect(['action' => 'index']);
              }

            }#edit

    /**
     * Delete method
     *
     * @param string|null $id Comunidade id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comunidade = $this->Comunidades->get($id);
        if ($this->Comunidades->delete($comunidade)) {
          $this->Flash->set('Comunidad eliminado con exito',['key'=>'message_comunidades', 'element'=>'success']);
        } else {
            $this->Flash->set('No se pudo eliminar el registro intente de nuevo',['key'=>'message_comunidades','element'=>'error']);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function publish($id){

      $this->autoRender=false;

      $this->request->allowMethod(['post', 'put']);
      if($this->request->is(['post', 'put'])){
        $comunidades = $this->Comunidades->get($id);
        $comunidades['status'] = ($comunidades['status'] == 0) ? 1 : 0;
        if ($this->Comunidades->save($comunidades)) {
          $this->Flash->set('Comunidades actualizado con exito',['key'=>'message_comunidades', 'element'=>'success']);
        }
      }else{
        $this->Flash->set('No se puede actualizar el status del banner',['key'=>'message_comunidades','element'=>'error']);
      }
      return $this->redirect(['action' => 'index']);

    }#publish
}
