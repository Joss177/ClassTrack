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

        $this->addBehavior('Timestamp');

        // Relación con horarios
        $this->hasMany('Horarios', [
            'foreignKey' => 'docente_id'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        // Nombre completo del docente (único dato usado desde el PDF)
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 100)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre del docente es obligatorio.');

        // Apellido (opcional porque el PDF no lo separa)
        $validator
            ->scalar('apellido')
            ->maxLength('apellido', 100)
            ->allowEmptyString('apellido');

        // Email (opcional porque el PDF no lo incluye)
        $validator
            ->scalar('email')
            ->maxLength('email', 150)
            ->allowEmptyString('email')
            ->email('email', false, 'Debe ingresar un email válido.');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Evitar docentes duplicados
        $rules->add(
            $rules->isUnique(['nombre']),
            ['errorField' => 'nombre', 'message' => 'Este docente ya existe.']
        );

        return $rules;
    }
}
