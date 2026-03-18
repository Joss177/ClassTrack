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


    public function index()
    {
        /* =====================================================
        SUBIR PDF Y PROCESAR CON PYTHON
        ====================================================== */
        if ($this->request->is('post')) {

            $archivo = $this->request->getData('archivoHorario');

            if (!$archivo || $archivo['error'] != 0) {
                $this->Flash->error("Error al subir archivo");
                return $this->redirect($this->referer());
            }

            if ($archivo['type'] !== 'application/pdf') {
                $this->Flash->error("El archivo no es un PDF válido");
                return $this->redirect($this->referer());
            }

            $uploadDir = WWW_ROOT . 'uploads';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $nombre = time() . '_' . $archivo['name'];
            $ruta   = $uploadDir . DS . $nombre;

            if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
                $this->Flash->error("No se pudo mover el archivo");
                return $this->redirect($this->referer());
            }

            /* =====================================================
            EJECUTAR PYTHON
            ====================================================== */
            $python    = "C:\\Users\\josue\\anaconda3\\python.exe";
            $script    = "C:\\xampp\\htdocs\\ClassTrack\\camaraView\\horarioAutomatic.py";
            $cmd       = "\"$python\" \"$script\" \"$ruta\" 2>&1";
            $resultado = shell_exec($cmd);

            if ($resultado === null) {
                $this->Flash->error("Python no se ejecutó correctamente");
                return $this->redirect($this->referer());
            }

            // Descartar warnings/texto antes del JSON
            $resultado = trim($resultado);
            $pos = strpos($resultado, '{');
            if ($pos === false) {
                $this->Flash->error("Python no devolvió JSON. Salida: " . substr($resultado, 0, 300));
                return $this->redirect($this->referer());
            }
            $resultado = substr($resultado, $pos);

            $data = json_decode($resultado, true);

            if (!$data || !isset($data['grupos'])) {
                $this->Flash->error("JSON inválido: " . json_last_error_msg());
                return $this->redirect($this->referer());
            }

            /* =====================================================
            INSERTAR DATOS
            ====================================================== */
            foreach ($data['grupos'] as $grupoData) {

                if (empty($grupoData['nombre']) || strpos($grupoData['nombre'], 'ERROR') === 0) {
                    continue;
                }

                /* ── 1. GRUPO ─────────────────────────────────── */
                $grupo = $this->Grupos->find()
                    ->where(['Grupos.nombre' => trim($grupoData['nombre'])])
                    ->first();

                if (!$grupo) {
                    $grupo = $this->Grupos->newEntity([
                        'nombre' => trim($grupoData['nombre'])
                    ]);
                    if (!$this->Grupos->save($grupo)) {
                        \Cake\Log\Log::error('Grupo no guardado: ' . json_encode($grupo->getErrors()));
                        continue;
                    }
                }

                $grupo_id    = $grupo->id;
                $mapMaterias = [];

                /* ── 2. DOCENTES Y MATERIAS ───────────────────── */
                foreach ($grupoData['materias'] as $materiaData) {

                    if (empty($materiaData['codigo']) || empty($materiaData['nombre'])) {
                        continue;
                    }

                    // FIX duplicados: normalizar espacios del nombre del docente
                    $nombreDocente = !empty($materiaData['docente'])
                        ? trim(preg_replace('/\s+/', ' ', $materiaData['docente']))
                        : 'Sin asignar';

                    // Buscar docente con nombre normalizado
                    $docente = $this->Docentes->find()
                        ->where(['Docentes.nombre' => $nombreDocente])
                        ->first();

                    if (!$docente) {
                        $docente = $this->Docentes->newEntity([
                            'nombre' => $nombreDocente
                        ]);
                        if (!$this->Docentes->save($docente)) {
                            \Cake\Log\Log::error('Docente no guardado: ' . json_encode($docente->getErrors()));
                            continue;
                        }
                    }

                    $docente_id = $docente->id;

                    // Buscar materia por codigo
                    $materia = $this->Materias->find()
                        ->where(['Materias.codigo' => $materiaData['codigo']])
                        ->first();

                    if (!$materia) {
                        $materia = $this->Materias->newEntity([
                            'codigo' => $materiaData['codigo'],
                            'nombre' => $materiaData['nombre'],
                            'color'  => $materiaData['color'] ?? '#60a5fa',
                        ]);
                        if (!$this->Materias->save($materia)) {
                            \Cake\Log\Log::error('Materia no guardada: ' . json_encode($materia->getErrors()));
                            continue;
                        }
                    }

                    $mapMaterias[$materiaData['codigo']] = [
                        'materia_id' => $materia->id,
                        'docente_id' => $docente_id,
                    ];
                }

                /* ── 3. HORARIOS ──────────────────────────────── */
                foreach ($grupoData['horarios'] as $horarioData) {

                    $codigo = $horarioData['codigo'] ?? '';

                    if (!isset($mapMaterias[$codigo])) {
                        continue;
                    }

                    $materia_id = $mapMaterias[$codigo]['materia_id'];
                    $docente_id = $mapMaterias[$codigo]['docente_id'];

                    // Aula
                    $aulaNombre = !empty($horarioData['aula'])
                        ? trim($horarioData['aula'])
                        : 'SIN_AULA';

                    $aula = $this->Aulas->find()
                        ->where(['Aulas.nombre' => $aulaNombre])
                        ->first();

                    if (!$aula) {
                        $aula = $this->Aulas->newEntity([
                            'nombre' => $aulaNombre
                        ]);
                        if (!$this->Aulas->save($aula)) {
                            \Cake\Log\Log::error('Aula no guardada: ' . json_encode($aula->getErrors()));
                            continue;
                        }
                    }

                    // FIX horas: Python ya devuelve HH:MM con cero,
                    // pero por si acaso normalizamos también aquí
                    $horaInicio = $horarioData['hora_inicio'] ?? '';
                    $horaFin    = $horarioData['hora_fin']    ?? '';

                    // Asegurar formato HH:MM (añadir cero si falta)
                    if (strlen($horaInicio) === 4) { $horaInicio = '0' . $horaInicio; }
                    if (strlen($horaFin)    === 4) { $horaFin    = '0' . $horaFin;    }

                    // Evitar duplicado — UNIQUE KEY: (grupo_id, dia_semana, hora_inicio)
                    $existe = $this->Horarios->find()
                        ->where([
                            'Horarios.grupo_id'    => $grupo_id,
                            'Horarios.dia_semana'  => (int)$horarioData['dia_semana'],
                            'Horarios.hora_inicio' => $horaInicio,
                        ])
                        ->first();

                    if ($existe) {
                        continue;
                    }

                    $horario = $this->Horarios->newEntity([
                        'docente_id'  => $docente_id,
                        'materia_id'  => $materia_id,
                        'grupo_id'    => $grupo_id,
                        'aula_id'     => $aula->id,
                        'dia_semana'  => (int)$horarioData['dia_semana'],
                        'hora_inicio' => $horaInicio,
                        'hora_fin'    => $horaFin,
                    ]);

                    if (!$this->Horarios->save($horario)) {
                        \Cake\Log\Log::error(
                            "Horario no guardado — grupo=$grupo_id "
                            . "dia={$horarioData['dia_semana']} "
                            . "inicio=$horaInicio "
                            . "errores=" . json_encode($horario->getErrors())
                        );
                    }
                }
            }

            $this->Flash->success("Archivo procesado y datos guardados correctamente.");
            return $this->redirect(['action' => 'index']);
        }

        /* =====================================================
        CONSULTA NORMAL DEL HORARIO
        ====================================================== */
        $grupoId = $this->request->getQuery('grupo_id');

        $horario = $this->Horarios->newEntity();

        $docentes = $this->Docentes->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre'
        ]);

        $materias = $this->Materias->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre'
        ]);

        $grupos = $this->Grupos->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre'
        ]);

        $aulas = $this->Aulas->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre'
        ]);

        $query = $this->Horarios->find()
            ->contain(['Docentes', 'Materias', 'Grupos', 'Aulas']);

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
