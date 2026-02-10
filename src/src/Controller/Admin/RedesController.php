<?php
namespace App\Controller\Admin;

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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $redes = $this->paginate($this->Redes);

        $this->set(compact('redes'));
    }

    /**
     * View method
     *
     * @param string|null $id Rede id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rede = $this->Redes->get($id, [
            'contain' => [],
        ]);

        $this->set('rede', $rede);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rede = $this->Redes->newEntity();
        if ($this->request->is('post')) {
            $rede = $this->Redes->patchEntity($rede, $this->request->getData());
            if ($this->Redes->save($rede)) {
                $this->Flash->success(__('The rede has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The rede could not be saved. Please, try again.'));
        }
        $this->set(compact('rede'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Rede id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rede = $this->Redes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rede = $this->Redes->patchEntity($rede, $this->request->getData());
            if ($this->Redes->save($rede)) {
                $this->Flash->success(__('The rede has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The rede could not be saved. Please, try again.'));
        }
        $this->set(compact('rede'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Rede id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rede = $this->Redes->get($id);
        if ($this->Redes->delete($rede)) {
            $this->Flash->success(__('The rede has been deleted.'));
        } else {
            $this->Flash->error(__('The rede could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function publish($id){

      $this->autoRender=false;

      $this->request->allowMethod(['post', 'put']);
      if($this->request->is(['post', 'put'])){
        $rede = $this->Redes->get($id);
        $rede['status'] = ($rede['status'] == 0) ? 1 : 0;
        if ($this->Redes->save($rede)) {
          $this->Flash->set('Registro actualizado con exito',['key'=>'message_redes', 'element'=>'success']);
        }
      }else{
        $this->Flash->set('No se puede actualizar el status del registro',['key'=>'message_redes','element'=>'error']);
      }
      return $this->redirect(['action' => 'index']);

    }#publish
}
