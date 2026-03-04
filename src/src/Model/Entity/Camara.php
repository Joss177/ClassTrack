<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Camara extends Entity
{
    protected $_accessible = [
        'aula_id' => true,
        'estado' => true,
        'ultima_deteccion' => true,
        'created' => true,
        'modified' => true,
        'aula' => true
    ];
}
