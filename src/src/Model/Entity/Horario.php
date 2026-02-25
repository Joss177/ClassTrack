<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Horario extends Entity
{
    protected $_accessible = [
        'docente_id' => true,
        'materia_id' => true,
        'grupo_id'   => true,
        'aula_id'    => true,
        'dia_semana' => true,
        'hora_inicio'=> true,
        'hora_fin'   => true,
        'created'    => true,
        'modified'   => true,

        'docente' => true,
        'materia' => true,
        'grupo'   => true,
        'aula'    => true,
    ];
}
