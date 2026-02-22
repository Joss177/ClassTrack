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
            ->notEmptyString('nombre_completo', 'El nombre es obligatorio')
            ->notEmptyString('correo', 'El correo es obligatorio')
            ->email('correo', 'Debe ser un correo válido')
            ->notEmptyString('password', 'La contraseña es obligatoria')
            ->minLength('password', 6, 'Mínimo 6 caracteres');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(
            ['correo'],
            'El correo ya está registrado'
        ));

        return $rules;
    }
}
