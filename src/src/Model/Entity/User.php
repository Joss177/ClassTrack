<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class User extends Entity
{
    protected $_accessible = [
        'nombre_completo' => true,
        'correo' => true,
        'password' => true,
        'group_id' => true,
        'created' => true,
        'modified' => true,
    ];

    protected $_hidden = [
        'password',
    ];
}
