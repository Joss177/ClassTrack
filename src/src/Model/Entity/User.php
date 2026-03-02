<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity
{
    // Campos que pueden ser modificados masivamente
    protected $_accessible = [
        'nombre_completo' => true,
        'correo' => true,
        'password' => true,
        'group_id' => true,
        'tema' => true,
        'created' => true,
        'modified' => true,
    ];

    // Campos ocultos al serializar (ej. JSON)
    protected $_hidden = [
        'password',
    ];

    // Hash automático al asignar contraseña
    protected function _setPassword($password)
    {
        if (!empty($password)) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
