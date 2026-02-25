<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class AulasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('aulas');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

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
            ->maxLength('nombre', 100, 'El nombre no puede exceder 100 caracteres.')
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre es obligatorio.');

        // Capacidad
        $validator
            ->integer('capacidad', 'La capacidad debe ser un número entero.')
            ->greaterThan('capacidad', 0, 'La capacidad debe ser mayor a 0.')
            ->requirePresence('capacidad', 'create')
            ->notEmptyString('capacidad', 'La capacidad es obligatoria.');

        // Piso
        $validator
            ->integer('piso', 'El piso debe ser un número entero.')
            ->greaterThanOrEqual('piso', 0, 'El piso no puede ser negativo.')
            ->requirePresence('piso', 'create')
            ->notEmptyString('piso', 'El piso es obligatorio.');

        // Edificio
        $validator
            ->scalar('edificio')
            ->maxLength('edificio', 50, 'El edificio no puede exceder 50 caracteres.')
            ->requirePresence('edificio', 'create')
            ->notEmptyString('edificio', 'El edificio es obligatorio.');

        // Tiene cámara
        $validator
            ->boolean('tiene_camara', 'El valor de cámara debe ser verdadero o falso.')
            ->allowEmptyString('tiene_camara');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Evitar nombres duplicados en el mismo edificio
        $rules->add($rules->isUnique(
            ['nombre', 'edificio'],
            'Ya existe un aula con ese nombre en ese edificio.'
        ));

        return $rules;
    }
}
