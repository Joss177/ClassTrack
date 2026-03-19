<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\Event\Event;
use ArrayObject;

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

        // Nombre
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 150)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'El nombre de la materia es obligatorio.');

        // Código (AHORA acepta minúsculas)
        $validator
            ->scalar('codigo')
            ->maxLength('codigo', 50)
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo', 'El código es obligatorio.')
            ->add('codigo', 'formato', [
                'rule' => ['custom', '/^[A-Za-z0-9\-]+$/'],
                'message' => 'El código solo puede contener letras, números y guiones.'
            ]);

        // Descripción
        $validator
            ->scalar('descripcion')
            ->allowEmptyString('descripcion');

        // Color
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

    // 🔥 Normaliza antes de guardar
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (!empty($data['codigo'])) {
            $data['codigo'] = strtoupper(trim($data['codigo']));
        }

        if (!empty($data['nombre'])) {
            $data['nombre'] = trim($data['nombre']);
        }
    }

    public function buildRules(RulesChecker $rules)
    {
        // SIN reglas de unicidad → permite duplicados
        return $rules;
    }
}
