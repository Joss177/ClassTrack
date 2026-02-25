<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;

class AulasController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Paginator');

        $this->viewBuilder()->setLayout('admin');
    }

    /*
     * LISTAR
     */
    public function index()
    {
        $this->paginate = [
            'order' => ['Aulas.id' => 'DESC'],
            'limit' => 20
        ];

        $aulas = $this->paginate($this->Aulas);

        $this->set(compact('aulas'));
    }

    /*
     * AGREGAR
     */
    public function add()
    {
        $aula = $this->Aulas->newEntity();

        if ($this->request->is('post')) {

            $aula = $this->Aulas->patchEntity($aula, $this->request->getData());

            if ($this->Aulas->save($aula)) {
                $this->Flash->success(__('El aula fue creada correctamente.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('No se pudo crear el aula. Verifica los datos.'));
        }

        $this->set(compact('aula'));
    }

    /*
     * EDITAR
     */
    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Aula no encontrada.'));
        }

        $aula = $this->Aulas->get($id);

        if ($this->request->is(['post', 'put', 'patch'])) {

            $aula = $this->Aulas->patchEntity($aula, $this->request->getData());

            if ($this->Aulas->save($aula)) {
                $this->Flash->success(__('El aula fue actualizada correctamente.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('No se pudo actualizar el aula. Verifica los datos.'));
        }

        $this->set(compact('aula'));
    }

    /*
     * ELIMINAR
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if (!$id) {
            throw new NotFoundException(__('Aula no encontrada.'));
        }

        $aula = $this->Aulas->get($id);

        if ($this->Aulas->delete($aula)) {
            $this->Flash->success(__('El aula fue eliminada correctamente.'));
        } else {
            $this->Flash->error(__('No se pudo eliminar el aula.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
