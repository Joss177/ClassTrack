<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

class UsersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmptyString('nombre_completo')
            ->notEmptyString('correo')
            ->email('correo')
            ->notEmptyString('password');

        return $validator;
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isDirty('password') && !empty($entity->password)) {
            $entity->password = (new DefaultPasswordHasher)->hash($entity->password);
        }
    }
}
