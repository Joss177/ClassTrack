<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\Event\EventInterface;
use ArrayObject;

class HorariosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('horarios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // Relaciones
        $this->belongsTo('Docentes', [
            'foreignKey' => 'docente_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Materias', [
            'foreignKey' => 'materia_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Grupos', [
            'foreignKey' => 'grupo_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Aulas', [
            'foreignKey' => 'aula_id',
            'joinType' => 'INNER'
        ]);
    }

    // ================= VALIDACIONES =================

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('docente_id')
            ->requirePresence('docente_id')
            ->notEmptyString('docente_id');

        $validator
            ->integer('materia_id')
            ->requirePresence('materia_id')
            ->notEmptyString('materia_id');

        $validator
            ->integer('grupo_id')
            ->requirePresence('grupo_id')
            ->notEmptyString('grupo_id');

        $validator
            ->integer('aula_id')
            ->requirePresence('aula_id')
            ->notEmptyString('aula_id');

        $validator
            ->integer('dia_semana')
            ->range('dia_semana', [1, 5], 'Día inválido')
            ->requirePresence('dia_semana')
            ->notEmptyString('dia_semana');

        $validator
            ->time('hora_inicio')
            ->requirePresence('hora_inicio')
            ->notEmptyTime('hora_inicio');

        $validator
            ->time('hora_fin')
            ->requirePresence('hora_fin')
            ->notEmptyTime('hora_fin')
            ->add('hora_fin', 'custom', [
                'rule' => function ($value, $context) {
                    if (empty($context['data']['hora_inicio'])) {
                        return false;
                    }
                    return $value > $context['data']['hora_inicio'];
                },
                'message' => 'La hora fin debe ser mayor a la hora inicio'
            ]);

        return $validator;
    }

    // ================= REGLAS DE INTEGRIDAD =================

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['docente_id'], 'Docentes'));
        $rules->add($rules->existsIn(['materia_id'], 'Materias'));
        $rules->add($rules->existsIn(['grupo_id'], 'Grupos'));
        $rules->add($rules->existsIn(['aula_id'], 'Aulas'));

        // Validación anti-traslape
        $rules->add(function ($entity, $options) {

            $query = $this->find()
                ->where([
                    'aula_id' => $entity->aula_id,
                    'dia_semana' => $entity->dia_semana,
                    'OR' => [
                        [
                            'hora_inicio <' => $entity->hora_fin,
                            'hora_fin >' => $entity->hora_inicio
                        ]
                    ]
                ]);

            if (!$entity->isNew()) {
                $query->where(['id !=' => $entity->id]);
            }

            return $query->count() === 0;

        }, 'noTraslape', [
            'errorField' => 'hora_inicio',
            'message' => 'Ya existe un horario que se traslapa en esa aula'
        ]);

        return $rules;
    }
}
