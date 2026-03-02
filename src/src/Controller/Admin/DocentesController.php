<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class DocentesController extends AppController
{
    public function index()
    {
        $docentes = $this->Docentes->find('all');
        $this->set(compact('docentes'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $docente = $this->Docentes->newEntity();
            $data = $this->request->getData();

            if (empty($data['nombre']) || empty($data['apellido']) || empty($data['email'])) {

                $this->Flash->error('Llenar todos los campos', ['key' => 'docente']);

            } else {

                $existe = $this->Docentes->find()
                    ->where(['email' => $data['email']])
                    ->first();

                if ($existe) {

                    $this->Flash->error(
                        'El correo del docente no se puede repetir',
                        ['key' => 'docente']
                    );

                } else {

                    $docente = $this->Docentes->patchEntity($docente, $data);

                    if ($this->Docentes->save($docente)) {

                        $this->Flash->success(
                            'Docente registrado correctamente',
                            ['key' => 'docente']
                        );

                    } else {

                        $this->Flash->error(
                            'Error al registrar el docente',
                            ['key' => 'docente']
                        );

                    }
                }
            }

            return $this->redirect(['action' => 'index']);
        }
    }

    public function edit($id = null)
    {
        $this->request->allowMethod(['post']);

        $docente = $this->Docentes->get($id);
        $data = $this->request->getData();

        $existe = $this->Docentes->find()
            ->where([
                'email' => $data['email'],
                'id !=' => $id
            ])
            ->first();

        if ($existe) {

            $this->Flash->error(
                'El correo del docente no se puede repetir',
                ['key' => 'docente']
            );

        } else {

            $docente = $this->Docentes->patchEntity($docente, $data);

            if ($this->Docentes->save($docente)) {

                $this->Flash->success(
                    'Docente actualizado correctamente',
                    ['key' => 'docente']
                );

            } else {

                $this->Flash->error(
                    'Error al actualizar el docente',
                    ['key' => 'docente']
                );

            }
        }

        return $this->redirect(['action' => 'index']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post']);

        $docente = $this->Docentes->get($id);

        if ($this->Docentes->delete($docente)) {
            $this->Flash->set(
                'Docente eliminado correctamente',
                [
                    'key' => 'docente',
                    'params' => ['class' => 'delete']
                ]
            );
        } else {
            $this->Flash->error(
                'Error al eliminar el docente',
                ['key' => 'docente']
            );
        }

        return $this->redirect(['action' => 'index']);
    }
}
