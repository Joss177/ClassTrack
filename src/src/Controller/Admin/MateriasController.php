<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class MateriasController extends AppController
{
    public function index()
    {
        $materias = $this->Materias->find('all');

        $this->set(compact('materias'));
    }

    public function add()
    {
        if ($this->request->is('post')) {

            $materia = $this->Materias->newEntity();

            $materia = $this->Materias->patchEntity(
                $materia,
                $this->request->getData()
            );

            $existe = $this->Materias->find()
                ->where(['codigo' => $materia->codigo])
                ->first();

            if ($existe) {

                $this->Flash->error(
                    'El código de la materia no se puede repetir',
                    ['key' => 'materia']
                );

            } elseif ($this->Materias->save($materia)) {

                $this->Flash->success(
                    'Materia registrada correctamente',
                    ['key' => 'materia']
                );

            } else {

                $this->Flash->error(
                    'Error al registrar la materia',
                    ['key' => 'materia']
                );

            }
        }

        return $this->redirect(['action' => 'index']);
    }


    public function edit($id = null)
    {
        $this->request->allowMethod(['post']);

        $materia = $this->Materias->get($id);

        $materia = $this->Materias->patchEntity(
            $materia,
            $this->request->getData()
        );

        $existe = $this->Materias->find()
            ->where([
                'codigo' => $materia->codigo,
                'id !=' => $id
            ])
            ->first();

        if ($existe) {

            $this->Flash->error(
                'El código de la materia no se puede repetir',
                ['key' => 'materia']
            );

        } elseif ($this->Materias->save($materia)) {

            $this->Flash->success(
                'Materia actualizada correctamente',
                ['key' => 'materia']
            );

        } else {

            $this->Flash->error(
                'Error al actualizar la materia',
                ['key' => 'materia']
            );

        }

        return $this->redirect(['action' => 'index']);
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post']);

        $materia = $this->Materias->get($id);

        if ($this->Materias->delete($materia)) {

            $this->Flash->set(
                'Materia eliminada correctamente',
                [
                    'key' => 'materia',
                    'params' => ['class' => 'delete']
                ]
            );

        } else {

            $this->Flash->error(
                'Error al eliminar la materia',
                ['key' => 'materia']
            );

        }

        return $this->redirect(['action' => 'index']);
    }
}
