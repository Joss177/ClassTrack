<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class HorariosController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Flash');

        $this->loadModel('Horarios');
        $this->loadModel('Docentes');
        $this->loadModel('Materias');
        $this->loadModel('Grupos');
        $this->loadModel('Aulas');
    }

    /**
     * INDEX
     */
    public function index()
    {
        $grupoId = $this->request->getQuery('grupo_id');

        $horario = $this->Horarios->newEntity();

        $docentes = $this->Docentes->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);

        $materias = $this->Materias->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);

        $grupos = $this->Grupos->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);

        $aulas = $this->Aulas->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);

        $query = $this->Horarios->find()
            ->contain(['Docentes', 'Materias', 'Grupos', 'Aulas']);

        // Filtro por grupo si viene seleccionado
        if (!empty($grupoId)) {
            $query->where(['Horarios.grupo_id' => $grupoId]);
        }

        $horarios = $query->all();

        $this->set(compact(
            'horario',
            'docentes',
            'materias',
            'grupos',
            'aulas',
            'horarios',
            'grupoId'
        ));
    }

    /**
     * ADD
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $horario = $this->Horarios->newEntity();

        // En Cake 3.8 se usa getData()
        $data = $this->request->getData();

        $horario = $this->Horarios->patchEntity($horario, $data);

        if ($this->Horarios->save($horario)) {

            $this->Flash->success('El horario se guardó correctamente.');

        } else {
            $errors = $horario->getErrors();

            if (!empty($errors)) {
                $this->Flash->error('Error al guardar: ' . json_encode($errors));
            } else {
                $this->Flash->error('No se pudo guardar el horario.');
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    public function mover()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $data = $this->request->input('json_decode', true);

        $horario = $this->Horarios->get($data['id']);

        $existe = $this->Horarios->find()
            ->where([
                'grupo_id' => $data['grupo_id'],
                'dia_semana' => $data['dia_semana'],
                'id !=' => $data['id'],
                'hora_inicio <' => $data['hora_fin'],
                'hora_fin >' => $data['hora_inicio']
            ])
            ->count();

        if ($existe > 0) {
            echo json_encode(['success' => false]);
            return;
        }

        $horario->grupo_id = $data['grupo_id'];
        $horario->dia_semana = $data['dia_semana'];
        $horario->hora_inicio = $data['hora_inicio'];
        $horario->hora_fin = $data['hora_fin'];

        if ($this->Horarios->save($horario)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function delete($id = null)
{
    $this->request->allowMethod(['post']);

    $horario = $this->Horarios->get($id);

    if ($this->Horarios->delete($horario)) {
        $this->Flash->success('Horario eliminado.');
    } else {
        $this->Flash->error('No se pudo eliminar.');
    }

    return $this->redirect(['action' => 'index']);
}

public function edit($id = null)
{
    $this->request->allowMethod(['post','put']);

    $horario = $this->Horarios->get($id);

    $horario = $this->Horarios->patchEntity($horario, $this->request->getData());

    if ($this->Horarios->save($horario)) {
        $this->Flash->success('Horario actualizado');
    } else {
        $this->Flash->error('Error al actualizar');
    }

    return $this->redirect(['action'=>'index']);
}
}
