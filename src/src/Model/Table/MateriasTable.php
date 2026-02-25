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
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 150, 'El nombre no puede exceder 150 caracteres.')
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre es obligatorio.');

        $validator
            ->scalar('codigo')
            ->maxLength('codigo', 50, 'El código no puede exceder 50 caracteres.')
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo', 'El código es obligatorio.');

        $validator
            ->add('codigo', 'formato', [
                'rule' => ['custom', '/^[A-Z0-9\-]+$/'],
                'message' => 'El código solo puede contener letras mayúsculas, números y guiones.'
            ]);

        $validator
            ->scalar('descripcion')
            ->allowEmptyString('descripcion');

        $validator
            ->scalar('color')
            ->maxLength('color', 7)
            ->requirePresence('color', 'create')
            ->notEmptyString('color', 'El color es obligatorio.')
            ->add('color', 'formatoHex', [
                'rule' => ['custom', '/^#[A-Fa-f0-9]{6}$/'],
                'message' => 'El color debe estar en formato hexadecimal válido (ej: #3b82f6).'
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['codigo'], 'El código ya está registrado.'));

        return $rules;
    }
}
