<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class MateriasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('materias');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // Relación con horarios
        $this->hasMany('Horarios', [
            'foreignKey' => 'materia_id'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        // Nombre de la materia (viene del PDF)
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 150)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre de la materia es obligatorio.');

        // Código de la materia (clave del PDF)
        $validator
            ->scalar('codigo')
            ->maxLength('codigo', 50)
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo', 'El código es obligatorio.')
            ->add('codigo', 'formato', [
                'rule' => ['custom', '/^[A-Z0-9\-]+$/'],
                'message' => 'El código solo puede contener letras mayúsculas, números y guiones.'
            ]);

        // Descripción (opcional, el PDF no la incluye)
        $validator
            ->scalar('descripcion')
            ->allowEmptyString('descripcion');

        // Color (opcional porque el PDF no lo trae, se puede usar default de la BD)
        $validator
            ->scalar('color')
            ->maxLength('color', 7)
            ->allowEmptyString('color')
            ->add('color', 'formatoHex', [
                'rule' => ['custom', '/^#[A-Fa-f0-9]{6}$/'],
                'message' => 'El color debe estar en formato hexadecimal válido.'
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        // Evitar materias duplicadas
        $rules->add(
            $rules->isUnique(['codigo']),
            ['errorField' => 'codigo', 'message' => 'Este código de materia ya existe.']
        );

        return $rules;
    }
}
