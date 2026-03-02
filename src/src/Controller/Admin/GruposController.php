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

        $existe = $this->Grupos->find()
            ->where(['nombre' => $grupo->nombre])
            ->first();

        if ($existe) {

            $this->Flash->set(
                'El nombre del grupo no se puede repetir',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'error']
                ]
            );

        } elseif ($this->Grupos->save($grupo)) {

            $this->Flash->set(
                'Grupo agregado correctamente',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'success']
                ]
            );

        } else {

            $this->Flash->set(
                'Error al agregar el grupo',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'error']
                ]
            );

        }

        return $this->redirect(['action' => 'index']);
    }


    public function edit($id = null)
    {
        $this->request->allowMethod(['post']);

        $grupo = $this->Grupos->get($id);
        $grupo = $this->Grupos->patchEntity($grupo, $this->request->getData());

        $existe = $this->Grupos->find()
            ->where([
                'nombre' => $grupo->nombre,
                'id !=' => $id
            ])
            ->first();

        if ($existe) {

            $this->Flash->set(
                'El nombre del grupo no se puede repetir',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'error']
                ]
            );

        } elseif ($this->Grupos->save($grupo)) {

            $this->Flash->set(
                'Grupo actualizado correctamente',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'success']
                ]
            );

        } else {

            $this->Flash->set(
                'Error al actualizar el grupo',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'error']
                ]
            );

        }

        return $this->redirect(['action' => 'index']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $grupo = $this->Grupos->get($id);

        if ($this->Grupos->delete($grupo)) {

            $this->Flash->set(
                'Grupo eliminado correctamente',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'delete']
                ]
            );

        } else {

            $this->Flash->set(
                'No se pudo eliminar el grupo',
                [
                    'key' => 'grupo',
                    'params' => ['class' => 'error']
                ]
            );

        }

        return $this->redirect(['action' => 'index']);
    }

}
