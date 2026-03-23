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

            $resultado = trim($resultado);
            $pos = strpos($resultado, '{');
            if ($pos === false) {
                $this->Flash->error("Python no devolvió JSON. Salida: " . substr($resultado, 0, 300));
                return $this->redirect($this->referer());
            }
            $resultado = substr($resultado, $pos);

            $data = json_decode($resultado, true);

            if (!$data || !isset($data['aulas'])) {
                $this->Flash->error("JSON inválido: " . json_last_error_msg());
                return $this->redirect($this->referer());
            }

            /* =====================================================
            INSERTAR DATOS (estructura por AULAS)
            ====================================================== */
            $colores = [
                '#f87171','#34d399','#fbbf24','#60a5fa',
                '#a78bfa','#f472b6','#22d3ee','#0ea5e9',
                '#10b981','#ef4444','#d97706','#4b5563',
                '#16a34a','#3b82f6','#e879f9','#f97316',
            ];
            $colorIdx = 0;

            foreach ($data['aulas'] as $aulaData) {

                if (empty($aulaData['nombre'])) continue;

                /* ── 1. AULA ──────────────────────────────────── */
                $aulaNombre = trim($aulaData['nombre']);

                $aula = $this->Aulas->find()
                    ->where(['Aulas.nombre' => $aulaNombre])
                    ->first();

                if (!$aula) {
                    $aula = $this->Aulas->newEntity(['nombre' => $aulaNombre]);
                    if (!$this->Aulas->save($aula)) {
                        \Cake\Log\Log::error('Aula no guardada: ' . json_encode($aula->getErrors()));
                        continue;
                    }
                }

                /* ── 2. HORARIOS DE ESTA AULA ─────────────────── */
                foreach ($aulaData['horarios'] as $h) {

                    /* ── GRUPO ── */
                    $grupoNombre = trim($h['grupo'] ?? 'SIN_GRUPO');

                    $grupo = $this->Grupos->find()
                        ->where(['Grupos.nombre' => $grupoNombre])
                        ->first();

                    if (!$grupo) {
                        $grupo = $this->Grupos->newEntity(['nombre' => $grupoNombre]);
                        if (!$this->Grupos->save($grupo)) {
                            \Cake\Log\Log::error('Grupo no guardado: ' . json_encode($grupo->getErrors()));
                            continue;
                        }
                    }

                    /* ── DOCENTE ── */
                    $nombreDocente = trim(preg_replace('/\s+/', ' ', $h['docente'] ?? 'Sin asignar'));

                    $docente = $this->Docentes->find()
                        ->where(['Docentes.nombre' => $nombreDocente])
                        ->first();

                    if (!$docente) {
                        $docente = $this->Docentes->newEntity(['nombre' => $nombreDocente]);
                        if (!$this->Docentes->save($docente)) {
                            \Cake\Log\Log::error('Docente no guardado: ' . json_encode($docente->getErrors()));
                            continue;
                        }
                    }

                    /* ── MATERIA ── */
                    $codigo = trim($h['codigo'] ?? '');
                    if (empty($codigo)) continue;

                    $materia = $this->Materias->find()
                        ->where(['Materias.codigo' => $codigo])
                        ->first();

                    if (!$materia) {
                        $materia = $this->Materias->newEntity([
                            'codigo' => $codigo,
                            'nombre' => $h['materia'] ?? 'SIN NOMBRE',
                            'color'  => $colores[$colorIdx % count($colores)],
                        ]);
                        $colorIdx++;
                        if (!$this->Materias->save($materia)) {
                            \Cake\Log\Log::error('Materia no guardada: ' . json_encode($materia->getErrors()));
                            continue;
                        }
                    }

                    /* ── HORAS ── */
                    $horaInicio = $h['hora_inicio'] ?? '';
                    $horaFin    = $h['hora_fin']    ?? '';
                    if (strlen($horaInicio) === 4) { $horaInicio = '0' . $horaInicio; }
                    if (strlen($horaFin)    === 4) { $horaFin    = '0' . $horaFin;    }

                    /* ── EVITAR DUPLICADO ── */
                    $existe = $this->Horarios->find()
                        ->where([
                            'Horarios.aula_id'     => $aula->id,
                            'Horarios.dia_semana'  => (int)$h['dia_semana'],
                            'Horarios.hora_inicio' => $horaInicio,
                        ])
                        ->first();

                    if ($existe) continue;

                    /* ── GUARDAR HORARIO ── */
                    $horario = $this->Horarios->newEntity([
                        'docente_id'  => $docente->id,
                        'materia_id'  => $materia->id,
                        'grupo_id'    => $grupo->id,
                        'aula_id'     => $aula->id,
                        'dia_semana'  => (int)$h['dia_semana'],
                        'hora_inicio' => $horaInicio,
                        'hora_fin'    => $horaFin,
                    ]);

                    if (!$this->Horarios->save($horario)) {
                        \Cake\Log\Log::error(
                            "Horario no guardado — aula={$aula->id} "
                            . "dia={$h['dia_semana']} inicio=$horaInicio "
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
        $aulaId = $this->request->getQuery('aula_id');

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

        if (!empty($aulaId)) {
            $query->where(['Horarios.aula_id' => $aulaId]);
        }

        $horarios = $query->all();

        $this->set(compact(
            'horario',
            'docentes',
            'materias',
            'grupos',
            'aulas',
            'horarios',
            'aulaId'
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
