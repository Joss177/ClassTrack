<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class GroupsTable extends Table{

    public function initialize(array $config){
      $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator){
      $validator
        ->notEmpty('name');
      $validator
        ->notEmpty('name', 'El nombre no puede quedar vacío');

      return $validator;
    }

    public function validationUpdate($validator){

      $validator
        ->notEmpty('name');
      $validator
        ->notEmpty('name', 'El nombre no puede quedar vacío');

      return $validator;
    }

}
