<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class DocentesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('docentes');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        // Si usas created y modified en la tabla
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        // Nombre
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 100, 'Máximo 100 caracteres.')
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre es obligatorio.')
            ->regex(
                'nombre',
                '/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/',
                'El nombre solo puede contener letras.'
            );

        // Apellido
        $validator
            ->scalar('apellido')
            ->maxLength('apellido', 100, 'Máximo 100 caracteres.')
            ->requirePresence('apellido', 'create')
            ->notEmptyString('apellido', 'El apellido es obligatorio.')
            ->regex(
                'apellido',
                '/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/',
                'El apellido solo puede contener letras.'
            );

        // Email
        $validator
            ->scalar('email')
            ->maxLength('email', 150, 'Máximo 150 caracteres.')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'El email es obligatorio.')
            ->email('email', false, 'Debe ingresar un email válido.');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Evitar correos duplicados
        $rules->add(
            $rules->isUnique(['email']),
            ['errorField' => 'email', 'message' => 'Este email ya está registrado.']
        );

        return $rules;
    }
}
