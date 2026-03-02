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
        $this->request->allowMethod(['post']);

        $aula = $this->Aulas->newEntity();
        $data = $this->request->getData();

        $existe = $this->Aulas->find()
            ->where(['nombre' => $data['nombre']])
            ->first();

        if ($existe) {

            $this->Flash->error(
                'El nombre del aula no se puede repetir.',
                ['key' => 'aula']
            );

            return $this->redirect(['action' => 'index']);
        }

        $aula = $this->Aulas->patchEntity($aula, $data);

        if ($this->Aulas->save($aula)) {

            $this->Flash->success(
                'El aula fue creada correctamente.',
                ['key' => 'aula']
            );

        } else {

            $this->Flash->error(
                'No se pudo crear el aula. Verifica los datos.',
                ['key' => 'aula']
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    /*
     * EDITAR
     */
    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException('Aula no encontrada.');
        }

        $aula = $this->Aulas->get($id);

        if ($this->request->is(['post', 'put', 'patch'])) {

            $data = $this->request->getData();

            $existe = $this->Aulas->find()
                ->where([
                    'nombre' => $data['nombre'],
                    'id !=' => $id
                ])
                ->first();

            if ($existe) {

                $this->Flash->error(
                    'El nombre del aula no se puede repetir.',
                    ['key' => 'aula']
                );

            } else {

                $aula = $this->Aulas->patchEntity($aula, $data);

                if ($this->Aulas->save($aula)) {

                    $this->Flash->success(
                        'El aula fue actualizada correctamente.',
                        ['key' => 'aula']
                    );

                    return $this->redirect(['action' => 'index']);
                }

                $this->Flash->error(
                    'No se pudo actualizar el aula. Verifica los datos.',
                    ['key' => 'aula']
                );
            }
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
            throw new NotFoundException('Aula no encontrada.');
        }

        $aula = $this->Aulas->get($id);

        if ($this->Aulas->delete($aula)) {

            $this->Flash->set(
                'El aula fue eliminada correctamente.',
                [
                    'key' => 'aula',
                    'params' => ['class' => 'delete']
                ]
            );

        } else {

            $this->Flash->error(
                'No se pudo eliminar el aula.',
                ['key' => 'aula']
            );
        }

        return $this->redirect(['action' => 'index']);
    }
}
