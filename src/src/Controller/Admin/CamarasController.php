<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class CamarasController extends AppController{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Flash');

        $this->Flash->setConfig([
            'element' => 'default'
        ]);
    }

    public function index()
    {
        $camara = $this->Camaras->newEntity();

        $camaras = $this->Camaras->find()
            ->contain(['Aulas'])
            ->all();

        $aulas = $this->Camaras->Aulas->find()
            ->select(['id', 'nombre', 'capacidad', 'edificio', 'piso'])
            ->toArray();

        $this->set(compact('camara', 'aulas', 'camaras'));
    }

public function add()
{
    $camara = $this->Camaras->newEntity();

    if ($this->request->is('post')) {

        $data = $this->request->getData();

        // Verificar que el aula exista
        $aula = $this->Camaras->Aulas->find()
            ->where(['id' => $data['aula_id']])
            ->first();

        if (!$aula) {
            $this->Flash->error('El aula no existe.', ['key' => 'camara']);
            return $this->redirect(['action' => 'index']);
        }

        // Verificar si ya existe cámara en esa aula
        $existe = $this->Camaras->find()
            ->where(['aula_id' => $data['aula_id']])
            ->first();

        if ($existe) {
            $this->Flash->error('Ya existe una cámara registrada en esa aula.', ['key' => 'camara']);
            return $this->redirect(['action' => 'index']);
        }

        // Forzar capacidad desde BD
        $data['capacidad'] = $aula->capacidad;

        $camara = $this->Camaras->patchEntity($camara, $data);

        if ($this->Camaras->save($camara)) {

            // Arrancar script si la cámara es activa
            if ($camara->estado === 'activa') {
                $pythonPath = 'C:\\Users\\josue\\anaconda3\\python.exe';
                $scriptPath = 'C:\\xampp\\htdocs\\ClassTrack\\camaraView\\fotocam.py';
                pclose(popen("cmd /c start /B \"\" \"$pythonPath\" \"$scriptPath\"", "r"));
            }

            $this->Flash->success('Cámara registrada correctamente.', ['key' => 'camara']);

        } else {
            $this->Flash->error('No se pudo registrar la cámara.', ['key' => 'camara']);
        }
    }

    return $this->redirect(['action' => 'index']);
}

public function edit($id)
{
    $camara = $this->Camaras->get($id);

    if ($this->request->is(['post', 'put', 'patch'])) {

        $data = $this->request->getData();

        // Si cambió de aula, verificar que no exista otra cámara ahí
        if ($data['aula_id'] != $camara->aula_id) {

            $existe = $this->Camaras->find()
                ->where([
                    'aula_id' => $data['aula_id']
                ])
                ->first();

            if ($existe) {
                $this->Flash->error('Ya existe una cámara en esa aula.', ['key' => 'camara']);
                return $this->redirect(['action' => 'index']);
            }
        }

        $camara = $this->Camaras->patchEntity($camara, $data);

        if ($this->Camaras->save($camara)) {

            // Detener Python si se inactiva o pone en mantenimiento
            if ($camara->estado === 'inactiva' || $camara->estado === 'mantenimiento') {
                shell_exec('taskkill /F /IM python.exe 2>&1');
            }

            // Arrancar Python si se reactiva
            if ($camara->estado === 'activa') {
                $pythonPath = 'C:\\Users\\josue\\anaconda3\\python.exe';
                $scriptPath = 'C:\\xampp\\htdocs\\ClassTrack\\camaraView\\fotocam.py';
                pclose(popen("cmd /c start /B \"\" \"$pythonPath\" \"$scriptPath\"", "r"));
            }

            $this->Flash->success('Cámara actualizada correctamente.', ['key' => 'camara']);

        } else {
            $this->Flash->error('No se pudo actualizar la cámara.', ['key' => 'camara']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $camara = $this->Camaras->get($id);

        if ($this->Camaras->delete($camara)) {
            $this->Flash->success('Cámara eliminada correctamente.', ['key' => 'camara']);
        } else {
            $this->Flash->error('No se pudo eliminar la cámara.', ['key' => 'camara']);
        }

        return $this->redirect(['action' => 'index']);
    }
}
