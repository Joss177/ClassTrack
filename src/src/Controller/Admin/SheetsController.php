<?php
namespace App\Controller\Admin;
use Cake\Event\Event;
use App\Controller\AppController;

class SheetsController extends AppController
{
    public function index()
    {
        // ================= MODELOS =================
        $this->loadModel('Horarios');

        // ================= FILTROS =================
        $docenteId = $this->request->query('docente_id');
        $materiaId = $this->request->query('materia_id');
        $grupoId   = $this->request->query('grupo_id');

        // ================= QUERY BASE =================
        $query = $this->Horarios->find()
            ->contain(['Docentes', 'Materias', 'Grupos'])
            ->order(['Docentes.nombre' => 'ASC']);

        if (!empty($docenteId)) {
            $query->where(['Horarios.docente_id' => $docenteId]);
        }

        if (!empty($materiaId)) {
            $query->where(['Horarios.materia_id' => $materiaId]);
        }

        if (!empty($grupoId)) {
            $query->where(['Horarios.grupo_id' => $grupoId]);
        }

        $horarios = $query->all();

        // ================= AGRUPAR POR DOCENTE =================
        $porDocente = [];

        foreach ($horarios as $h) {

            $nombreDocente = !empty($h->docente->nombre)
                ? $h->docente->nombre
                : 'Sin docente';

            if (!isset($porDocente[$nombreDocente])) {
                $porDocente[$nombreDocente] = [];
            }

            $materia = !empty($h->materia->nombre) ? $h->materia->nombre : '';
            $grupo   = !empty($h->grupo->nombre) ? $h->grupo->nombre : '';

            $texto = trim($materia . ' - ' . $grupo, ' -');

            if (!in_array($texto, $porDocente[$nombreDocente])) {
                $porDocente[$nombreDocente][] = $texto;
            }
        }

        // ================= FILTROS DEPENDIENTES =================

        // ===== DOCENTES =====
        $docentesQuery = $this->Horarios->find()
            ->contain(['Docentes'])
            ->distinct(['Horarios.docente_id']);

        if (!empty($materiaId)) {
            $docentesQuery->where(['Horarios.materia_id' => $materiaId]);
        }

        if (!empty($grupoId)) {
            $docentesQuery->where(['Horarios.grupo_id' => $grupoId]);
        }

        $docentesList = [];
        foreach ($docentesQuery as $h) {
            if (!empty($h->docente)) {
                $docentesList[$h->docente->id] = $h->docente->nombre;
            }
        }

        // ===== MATERIAS =====
        $materiasQuery = $this->Horarios->find()
            ->contain(['Materias'])
            ->distinct(['Horarios.materia_id']);

        if (!empty($docenteId)) {
            $materiasQuery->where(['Horarios.docente_id' => $docenteId]);
        }

        if (!empty($grupoId)) {
            $materiasQuery->where(['Horarios.grupo_id' => $grupoId]);
        }

        $materiasList = [];
        foreach ($materiasQuery as $h) {
            if (!empty($h->materia)) {
                $materiasList[$h->materia->id] = $h->materia->nombre;
            }
        }

        // ===== GRUPOS =====
        $gruposQuery = $this->Horarios->find()
            ->contain(['Grupos'])
            ->distinct(['Horarios.grupo_id']);

        if (!empty($docenteId)) {
            $gruposQuery->where(['Horarios.docente_id' => $docenteId]);
        }

        if (!empty($materiaId)) {
            $gruposQuery->where(['Horarios.materia_id' => $materiaId]);
        }

        $gruposList = [];
        foreach ($gruposQuery as $h) {
            if (!empty($h->grupo)) {
                $gruposList[$h->grupo->id] = $h->grupo->nombre;
            }
        }

        // ================= ENVIAR A VISTA =================
        $this->set(compact(
            'porDocente',
            'docentesList',
            'materiasList',
            'gruposList',
            'docenteId',
            'materiaId',
            'grupoId'
        ));
    }



    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $user = $this->Auth->user();

        if (!$user || $user['group_id'] != 2) {

            $this->Flash->error('No tienes permisos para acceder.');

            return $this->redirect([
                'prefix' => 'Admin',
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }
    }
}
