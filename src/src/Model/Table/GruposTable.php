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

        // Relación con horarios
        $this->hasMany('Horarios', [
            'foreignKey' => 'grupo_id'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        // Nombre del grupo (dato que viene del PDF)
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 50)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre del grupo es obligatorio.');

        // Cantidad de estudiantes (opcional porque el PDF no lo incluye)
        $validator
            ->integer('cantidad_estudiantes')
            ->allowEmptyString('cantidad_estudiantes')
            ->greaterThanOrEqual(
                'cantidad_estudiantes',
                0,
                'La cantidad no puede ser negativa'
            );

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Evitar grupos duplicados
        $rules->add(
            $rules->isUnique(['nombre']),
            ['errorField' => 'nombre', 'message' => 'Este grupo ya existe.']
        );

        return $rules;
    }
}
