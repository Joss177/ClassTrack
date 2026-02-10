<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Filosofias Controller
 *
 * @property \App\Model\Table\FilosofiasTable $Filosofias
 *
 * @method \App\Model\Entity\Filosofia[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilosofiasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $filosofias = $this->paginate($this->Filosofias);

        $this->set(compact('filosofias'));
    }

    /**
     * View method
     *
     * @param string|null $id Filosofia id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $filosofia = $this->Filosofias->get($id, [
            'contain' => [],
        ]);

        $this->set('filosofia', $filosofia);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $filosofia = $this->Filosofias->newEntity();
        if ($this->request->is('post')) {
            $filosofia = $this->Filosofias->patchEntity($filosofia, $this->request->getData());
            if ($this->Filosofias->save($filosofia)) {
              $this->Flash->set('Registro agregado con éxito',['key'=>'message_filosofias', 'element'=>'success']);
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->set('No se pudo agregar el registro',['key'=>'message_filosofias','element'=>'error']);
        }
        $this->set(compact('filosofia'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Filosofia id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $filosofia = $this->Filosofias->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $filosofia = $this->Filosofias->patchEntity($filosofia, $this->request->getData());
            if ($this->Filosofias->save($filosofia)) {
              $this->Flash->set('Registro agregado con éxito',['key'=>'message_filosofias', 'element'=>'success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->set('No se pudo agregar el registro',['key'=>'message_filosofias','element'=>'error']);
        }
        $this->set(compact('filosofia'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Filosofia id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $filosofia = $this->Filosofias->get($id);
        if ($this->Filosofias->delete($filosofia)) {
          $this->Flash->set('Registro agregado con éxito',['key'=>'message_filosofias', 'element'=>'success']);
        } else {
          $this->Flash->set('No se pudo agregar el registro',['key'=>'message_filosofias','element'=>'error']);
        }

        return $this->redirect(['action' => 'index']);
    }
}
