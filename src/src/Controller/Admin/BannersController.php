<?php
namespace App\Controller\Admin;

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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
     public function index()
     {
       $paginate = [
         'limit' => 10,
         'order' => [
           'Banners.created' => 'DESC'
         ]
       ];

       $this->loadComponent('Paginator');
       $banners = $this->Paginator->paginate($this->Banners->find('all'),$paginate);
       $this->set('banners', $banners);
     }

    /**
     * View method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $banner = $this->Banners->get($id, [
            'contain' => [],
        ]);

        $this->set('banner', $banner);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
     public function add(){

     $addBanner = $this->Banners->newEntity($this->request->getData(),['validate'=>false]);
     if($this->request->is('post') && !empty($this->request->getData())){
       $post = $this->Banners->patchEntity($addBanner, $this->request->getData(),['validate'=>'update']);
         if($addBanner['img']['error']==0){
           if ($this->Banners->save($addBanner)) {
             $this->Flash->set('Banner agregado con éxito',['key'=>'message_banner', 'element'=>'success']);
             return $this->redirect(['action' => 'index']);
           }else{
              $this->Flash->set('No se puede guardar el banner',['key'=>'message_banner', 'element'=>'error']);
              return $this->redirect(['action' => 'index']);
            }
         }else{
             $this->Flash->set('Es necesario incluir una imagen al banner,intente de nuevo',['key'=>'message_banner','element'=>'error']);
         }
     }
     $this->set('addBanner',$addBanner);
   }#add


    /**
     * Edit method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

       public function edit($id=null){

         if(!empty($id)){
           $banner = $this->Banners->get($id);
           if ($this->request->is(['post', 'put'])) {
             $this->Banners->patchEntity($banner, $this->request->getData(), ['validate'=>'update']);
             if(!empty($producto['img']['name'])){
               $fileName = str_replace(' ','_',$banner['img']['name']);

               $banner['img']['name'] = $fileName;
               $banner2 = $this->Banners->get($id);
               if($banner2['img'] != ""){
                 unlink(WWW_ROOT."files/Banners/img/".$banner2['img']);
               }
             }else{
               $banner['file_name'] = $this->request->getData('file_name');

             }

             if ($this->Banners->save($banner)) {
               $this->Flash->set('Banner actualizado con exito',['key'=>'message_banner', 'element'=>'success']);
               return $this->redirect(['action' => 'index']);
             }
             $this->Flash->set('No se puede editar el banner',['key'=>'message_banner','element'=>'error']);
           }

           $this->set('editBanner', $banner);
         }else{
           $this->Flash->set('El id es incorrecto',['key'=>'message_banner','element'=>'error']);
           return $this->redirect(['action' => 'index']);
         }

       }#edit

    /**
     * Delete method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function delete($id){

     $this->request->allowMethod(['post', 'delete']);
     if($this->request->is(['post', 'delete'])){
       $banner = $this->Banners->get($id);
       if ($this->Banners->delete($banner)) {
         $this->Flash->set('Banner eliminado con exito',['key'=>'message_banner', 'element'=>'success']);
         return $this->redirect(['action' => 'index']);
       }
     }
     $this->Flash->set('No se puede eliminar el banner',['key'=>'message_banner','element'=>'error']);


   }#delete

   public function publish($id){

     $this->autoRender=false;

     $this->request->allowMethod(['post', 'put']);
     if($this->request->is(['post', 'put'])){
       $banner = $this->Banners->get($id);
       $banner['status'] = ($banner['status'] == 0) ? 1 : 0;
       if ($this->Banners->save($banner)) {
         $this->Flash->set('Banner actualizado con exito',['key'=>'message_banner', 'element'=>'success']);
       }
     }else{
       $this->Flash->set('No se puede actualizar el status del banner',['key'=>'message_banner','element'=>'error']);
     }
     return $this->redirect(['action' => 'index']);

   }#publish
}
