<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class GruposController extends AppController
{

    public function index()
    {
        $grupos = $this->Grupos->find('all')->order(['id' => 'DESC']);
        $this->set(compact('grupos'));
    }


    public function add()
    {
        $this->request->allowMethod(['post']);

        $grupo = $this->Grupos->newEntity();
        $grupo = $this->Grupos->patchEntity($grupo, $this->request->getData());

        if ($this->Grupos->save($grupo)) {
            $this->Flash->success('Grupo agregado correctamente');
        } else {
            $this->Flash->error('Error al agregar el grupo');
        }

        return $this->redirect(['action' => 'index']);
    }


    public function edit($id = null)
    {
        $this->request->allowMethod(['post']);

        $grupo = $this->Grupos->get($id);
        $grupo = $this->Grupos->patchEntity($grupo, $this->request->getData());

        if ($this->Grupos->save($grupo)) {
            $this->Flash->success('Grupo actualizado correctamente');
        } else {
            $this->Flash->error('Error al actualizar el grupo');
        }

        return $this->redirect(['action' => 'index']);
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $grupo = $this->Grupos->get($id);

        if ($this->Grupos->delete($grupo)) {
            $this->Flash->success('Grupo eliminado correctamente');
        } else {
            $this->Flash->error('No se pudo eliminar el grupo');
        }

        return $this->redirect(['action' => 'index']);
    }

}
