<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class CamarasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('camaras');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // Relación con Aulas
        $this->belongsTo('Aulas', [
            'foreignKey' => 'aula_id',
            'joinType' => 'INNER'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('aula_id')
            ->requirePresence('aula_id', 'create')
            ->notEmptyString('aula_id', 'El aula es obligatoria');

        $validator
            ->scalar('estado')
            ->requirePresence('estado', 'create')
            ->notEmptyString('estado', 'El estado es obligatorio')
            ->add('estado', 'inList', [
                'rule' => ['inList', ['activa', 'inactiva', 'mantenimiento']],
                'message' => 'Estado inválido'
            ]);

        $validator
            ->dateTime('ultima_deteccion')
            ->allowEmptyDateTime('ultima_deteccion');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['aula_id'], 'Aulas'), [
            'errorField' => 'aula_id',
            'message' => 'El aula seleccionada no existe'
        ]);

        return $rules;
    }
}
