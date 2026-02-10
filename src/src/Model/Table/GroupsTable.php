<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class GroupsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('groups');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Users', [
            'foreignKey' => 'group_id',
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmptyString('name', 'El nombre del grupo es obligatorio');

        return $validator;
    }
}
