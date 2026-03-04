<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\RulesChecker;

class UsersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('nombre_completo', 'El nombre es obligatorio')
            ->notEmpty('correo', 'El correo es obligatorio')
            ->email('correo', false, 'Debe ser un correo válido')

            // Password obligatoria solo al crear
            ->notEmpty('password', 'La contraseña es obligatoria', 'create')
            ->minLength('password', 6, 'Mínimo 6 caracteres')

            // Tema
            ->inList('tema', ['claro', 'oscuro'], 'Tema inválido');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(
            ['correo'],
            'El correo ya está registrado'
        ));

        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }
}
