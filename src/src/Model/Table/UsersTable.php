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
            ->allowEmptyString('token')
            ->allowEmptyDateTime('token_expira')
            // Password obligatoria solo al crear
            ->notEmpty('password', 'La contraseña es obligatoria', 'create')
            ->minLength('password', 6, 'Mínimo 6 caracteres')

            // Tema
            ->inList('tema', ['claro', 'oscuro'], 'Tema inválido');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Validar correo único (normal)
        $rules->add($rules->isUnique(
            ['correo'],
            'El correo ya está registrado'
        ));

        // 🔥 Validar group_id SOLO cuando aplique
        $rules->add(function ($entity, $options) {

            // 👉 Si solo estás actualizando token o token_expira, NO validar group_id
            if ($entity->isDirty('token') || $entity->isDirty('token_expira')) {
                return true;
            }

            // 👉 Validación normal de group_id
            if (!empty($entity->group_id)) {
                return $this->Groups->exists(['id' => $entity->group_id]);
            }

            return false;

        }, 'validGroup', [
            'errorField' => 'group_id',
            'message' => 'El grupo no existe'
        ]);

        return $rules;
    }
}
