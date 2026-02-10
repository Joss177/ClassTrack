<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Filosofias Model
 *
 * @method \App\Model\Entity\Filosofia get($primaryKey, $options = [])
 * @method \App\Model\Entity\Filosofia newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Filosofia[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Filosofia|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Filosofia saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Filosofia patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Filosofia[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Filosofia findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FilosofiasTable extends Table
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

        $this->setTable('filosofias');
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
            ->scalar('mision')
            ->requirePresence('mision', 'create')
            ->notEmptyString('mision');

        $validator
            ->scalar('vision')
            ->requirePresence('vision', 'create')
            ->notEmptyString('vision');

        $validator
            ->scalar('valores')
            ->requirePresence('valores', 'create')
            ->notEmptyString('valores');

        return $validator;
    }
}
