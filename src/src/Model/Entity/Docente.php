<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Docente extends Entity
{
    protected $_accessible = [
        'nombre' => true,
        'apellido' => true,
        'email' => true,
        'created' => true,
        'modified' => true,
        'id' => false
    ];
}
