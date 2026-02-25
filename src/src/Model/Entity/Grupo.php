<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Grupo extends Entity
{
    protected $_accessible = [
        'nombre' => true,
        'cantidad_estudiantes' => true,
        'created' => true,
        'modified' => true
    ];
}
