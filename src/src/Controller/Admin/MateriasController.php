<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class MateriasController extends AppController
{
    public function index()
    {
        // Listado de materias
        $materias = $this->Materias->find('all');

        // Entidad nueva (para modal agregar)
        $materia = $this->Materias->newEntity();

        // Guardar desde modal agregar
        if ($this->request->is('post')) {

            $materia = $this->Materias->patchEntity(
                $materia,
                $this->request->getData()
            );

            if ($this->Materias->save($materia)) {
                $this->Flash->success('Materia guardada correctamente.');
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('Error al guardar la materia.');
        }

        $this->set(compact('materias', 'materia'));
    }

    public function edit($id = null)
    {
        $materia = $this->Materias->get($id);

        if ($this->request->is(['post', 'put', 'patch'])) {

            $materia = $this->Materias->patchEntity(
                $materia,
                $this->request->getData()
            );

            if ($this->Materias->save($materia)) {
                $this->Flash->success('Materia actualizada correctamente.');
            } else {
                $this->Flash->error('No se pudo actualizar la materia.');
            }

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $materia = $this->Materias->get($id);

        if ($this->Materias->delete($materia)) {
            $this->Flash->success('Materia eliminada correctamente.');
        } else {
            $this->Flash->error('No se pudo eliminar la materia.');
        }

        return $this->redirect(['action' => 'index']);
    }
}
