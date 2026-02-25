<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class GruposTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('grupos');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        // Nombre
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 50, 'El nombre no debe superar los 50 caracteres')
            ->requirePresence('nombre', 'create', 'El nombre es obligatorio')
            ->notEmptyString('nombre', 'El nombre no puede estar vacío');

        // Cantidad de estudiantes
        $validator
            ->integer('cantidad_estudiantes', 'Debe ser un número entero')
            ->requirePresence('cantidad_estudiantes', 'create', 'La cantidad es obligatoria')
            ->notEmptyString('cantidad_estudiantes', 'La cantidad no puede estar vacía')
            ->greaterThanOrEqual(
                'cantidad_estudiantes',
                0,
                'La cantidad no puede ser negativa'
            );

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Evitar nombres repetidos
        $rules->add(
            $rules->isUnique(['nombre'], 'Este grupo ya existe')
        );

        return $rules;
    }
}
