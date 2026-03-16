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

        // timestamps automáticos
        $this->addBehavior('Timestamp');

        // Relación con horarios
        $this->hasMany('Horarios', [
            'foreignKey' => 'aula_id'
        ]);

        // Relación con cámaras
        $this->hasMany('Camaras', [
            'foreignKey' => 'aula_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
    }

    public function validationDefault(Validator $validator)
    {

        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        // Nombre del aula (único dato que viene del PDF)
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 100)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre del aula es obligatorio.');

        // Capacidad (opcional)
        $validator
            ->integer('capacidad')
            ->allowEmptyString('capacidad');

        // Piso (opcional)
        $validator
            ->integer('piso')
            ->allowEmptyString('piso');

        // Edificio (opcional)
        $validator
            ->scalar('edificio')
            ->maxLength('edificio', 50)
            ->allowEmptyString('edificio');

        // Tiene cámara
        $validator
            ->boolean('tiene_camara')
            ->allowEmptyString('tiene_camara');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Evitar duplicados de aulas
        $rules->add(
            $rules->isUnique(['nombre']),
            ['errorField' => 'nombre', 'message' => 'Ya existe un aula con ese nombre.']
        );

        return $rules;
    }
}
