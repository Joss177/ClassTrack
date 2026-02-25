<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Materia extends Entity
{
    protected $_accessible = [
        'nombre' => true,
        'codigo' => true,
        'descripcion' => true,
        'color' => true,
        'created' => true,
        'modified' => true
    ];
}
