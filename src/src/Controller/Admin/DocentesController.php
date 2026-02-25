<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class DocentesController extends AppController
{
    public function index()
    {
        $docente = $this->Docentes->newEntity();
        $docentes = $this->Docentes->find('all');

        $this->set(compact('docente', 'docentes'));
    }

    public function add()
    {
        $docente = $this->Docentes->newEntity();

        if ($this->request->is('post')) {

            $docente = $this->Docentes->patchEntity(
                $docente,
                $this->request->getData()
            );

            if ($this->Docentes->save($docente)) {
                $this->Flash->success('Docente guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('Error al guardar el docente.');
        }

        $this->set(compact('docente'));
    }

    public function edit($id = null)
    {
        $this->request->allowMethod(['post']);

        $docente = $this->Docentes->get($id);

        $docente = $this->Docentes->patchEntity(
            $docente,
            $this->request->getData()
        );

        if ($this->Docentes->save($docente)) {
            $this->Flash->success('Docente actualizado correctamente.');
        } else {
            $this->Flash->error('Error al actualizar el docente.');
        }

        return $this->redirect(['action' => 'index']);
    }
   public function delete($id = null)
    {
        $this->request->allowMethod(['post']);

        $docente = $this->Docentes->get($id);

        if ($this->Docentes->delete($docente)) {
            $this->Flash->success('Docente eliminado correctamente.');
        } else {
            $this->Flash->error('Error al eliminar el docente.');
        }

        return $this->redirect(['action' => 'index']);
    }
}
