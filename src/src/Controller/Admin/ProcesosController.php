<?php
namespace App\Controller\Admin;

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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $procesos = $this->paginate($this->Procesos);

        $this->set(compact('procesos'));
    }



    /**
     * Edit method
     *
     * @param string|null $id Proceso id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $proceso = $this->Procesos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $proceso = $this->Procesos->patchEntity($proceso, $this->request->getData());
            if ($this->Procesos->save($proceso)) {
              $this->Flash->set('Registro agregado con éxito',['key'=>'message_procesos', 'element'=>'success']);
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->set('No se puede actualizar el status',['key'=>'message_procesos','element'=>'error']);
        }
        $this->set(compact('proceso'));
    }

}
