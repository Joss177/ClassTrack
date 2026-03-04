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

        $horarios = $this->Horarios->find()
            ->contain(['Docentes', 'Materias', 'Grupos', 'Aulas'])
            ->all();

        $this->set(compact(
            'horario',
            'docentes',
            'materias',
            'grupos',
            'aulas',
            'horarios'
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
}
