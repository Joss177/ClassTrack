<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Redes Model
 *
 * @method \App\Model\Entity\Rede get($primaryKey, $options = [])
 * @method \App\Model\Entity\Rede newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Rede[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Rede|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rede saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rede patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Rede[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Rede findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RedesTable extends Table
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

        $this->setTable('redes');
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
            ->scalar('facebook')
            ->maxLength('facebook', 255)
            ->requirePresence('facebook', 'create')
            ->notEmptyString('facebook');

        $validator
            ->scalar('instagram')
            ->maxLength('instagram', 255)
            ->requirePresence('instagram', 'create')
            ->notEmptyString('instagram');

        $validator
            ->scalar('whatsapp')
            ->maxLength('whatsapp', 255)
            ->requirePresence('whatsapp', 'create')
            ->notEmptyString('whatsapp');

        return $validator;
    }
}
