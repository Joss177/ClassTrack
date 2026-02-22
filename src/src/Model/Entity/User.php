<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

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

    protected function _setPassword($password)
    {
        if (!empty($password)) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
