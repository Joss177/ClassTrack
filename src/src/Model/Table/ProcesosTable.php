<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Procesos Model
 *
 * @method \App\Model\Entity\Proceso get($primaryKey, $options = [])
 * @method \App\Model\Entity\Proceso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Proceso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Proceso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Proceso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Proceso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Proceso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Proceso findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProcesosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('procesos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('tittle1')
            ->maxLength('tittle1', 255)
            ->requirePresence('tittle1', 'create')
            ->notEmptyString('tittle1');

        $validator
            ->scalar('description1')
            ->requirePresence('description1', 'create')
            ->notEmptyString('description1');

        $validator
            ->scalar('tittle2')
            ->maxLength('tittle2', 255)
            ->requirePresence('tittle2', 'create')
            ->notEmptyString('tittle2');

        $validator
            ->scalar('description2')
            ->requirePresence('description2', 'create')
            ->notEmptyString('description2');

        return $validator;
    }
}
