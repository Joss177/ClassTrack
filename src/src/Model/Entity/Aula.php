<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Aula extends Entity
{
    protected $_accessible = [
        'nombre' => true,
        'capacidad' => true,
        'piso' => true,
        'edificio' => true,
        'tiene_camara' => true,
        'created' => true,
        'modified' => true
    ];
}
