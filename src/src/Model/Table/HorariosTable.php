<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class HorariosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('horarios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // ================= RELACIONES =================

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
            ->requirePresence('docente_id', 'create')
            ->notEmptyString('docente_id');

        $validator
            ->integer('materia_id')
            ->requirePresence('materia_id', 'create')
            ->notEmptyString('materia_id');

        $validator
            ->integer('grupo_id')
            ->requirePresence('grupo_id', 'create')
            ->notEmptyString('grupo_id');

        $validator
            ->integer('aula_id')
            ->requirePresence('aula_id', 'create')
            ->notEmptyString('aula_id');

        $validator
            ->integer('dia_semana')
            ->requirePresence('dia_semana', 'create')
            ->notEmptyString('dia_semana')
            ->inList('dia_semana', [1,2,3,4,5], 'Día inválido');

        $validator
            ->scalar('hora_inicio')
            ->maxLength('hora_inicio', 5)
            ->requirePresence('hora_inicio', 'create')
            ->notEmptyString('hora_inicio')
            ->regex('hora_inicio', '/^\d{2}:\d{2}$/', 'Formato inválido (HH:MM)');

        $validator
            ->scalar('hora_fin')
            ->maxLength('hora_fin', 5)
            ->requirePresence('hora_fin', 'create')
            ->notEmptyString('hora_fin')
            ->regex('hora_fin', '/^\d{2}:\d{2}$/', 'Formato inválido (HH:MM)')
            ->add('hora_fin', 'horaMayor', [
                'rule' => function ($value, $context) {

                    if (empty($context['data']['hora_inicio'])) {
                        return false;
                    }

                    return strtotime($value) > strtotime($context['data']['hora_inicio']);
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


        $rules->add(function ($entity, $options) {

            if (empty($entity->aula_id) ||
                empty($entity->dia_semana) ||
                empty($entity->hora_inicio) ||
                empty($entity->hora_fin)) {
                return true;
            }

            $query = $this->find()
                ->where([
                    'aula_id' => $entity->aula_id,
                    'dia_semana' => $entity->dia_semana,
                    'hora_inicio <' => $entity->hora_fin,
                    'hora_fin >' => $entity->hora_inicio
                ]);

            // Si es edición, excluir el mismo registro
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
