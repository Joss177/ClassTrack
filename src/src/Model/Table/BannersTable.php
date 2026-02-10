<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Banners Model
 *
 * @method \App\Model\Entity\Banner get($primaryKey, $options = [])
 * @method \App\Model\Entity\Banner newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Banner[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Banner|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Banner saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Banner patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Banner[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Banner findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BannersTable extends Table
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

       $this->setTable('banners');
       $this->setDisplayField('id');
       $this->setPrimaryKey('id');

       $this->addBehavior('Timestamp');

       $this->addBehavior('Josegonzalez/Upload.Upload', [
         'img' => [
           'nameCallback' => function ($table, $entity, $data, $field, $settings) {
             $name = str_replace(' ', '', $data['name']);
             return strtolower($name);
           },
           'deleteCallback' => function ($path, $entity, $field, $settings) {
             // When deleting the entity, both the original and the thumbnail will be removed
             // when keepFilesOnDelete is set to false
             return [
               $path . $entity->{$field},
               $path . 'thumbnail-' . $entity->{$field}
             ];
           },
           'keepFilesOnDelete' => false
         ]
       ]);
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
            ->scalar('img')
            ->maxLength('img', 255)
            ->requirePresence('img', 'create')
            ->notEmptyString('img');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->scalar('color')
            ->maxLength('color', 255)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        return $validator;
    }

    public function validationUpdate($validator){

      $validator
      ->notEmpty('tittle', 'El titulo no puede quedar vacío');

      return $validator;
    }
}
