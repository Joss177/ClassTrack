<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Galerias Model
 *
 * @method \App\Model\Entity\Galeria get($primaryKey, $options = [])
 * @method \App\Model\Entity\Galeria newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Galeria[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Galeria|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Galeria saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Galeria patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Galeria[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Galeria findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GaleriasTable extends Table
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

        $this->setTable('galerias');
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
            ->integer('cantidad')
            ->allowEmptyString('cantidad');

        $validator
            ->allowEmptyString('status');

        return $validator;
    }

    public function validationUpdate($validator){

      return $validator;
    }
}
