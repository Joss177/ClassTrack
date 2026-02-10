<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table{

  public function initialize(array $config){
    $this->addBehavior('Timestamp');
  }

  public function validationDefault(Validator $validator){
    $validator
      ->notEmpty('name')
      ->notEmpty('email')
      ->notEmpty('password')
      ->requirePresence(['name','email','password']);

    return $validator;
  }

  public function validationUpdate($validator){

    $validator
      ->notEmpty('name')
      ->notEmpty('email')
      ->notEmpty('password');
    $validator
      ->notEmpty('name', 'El nombre no puede quedar vacío')
      ->notEmpty('email', 'El email no puede quedar vacío')
      ->notEmpty('password', 'La contraseña no puede quedar vacía');

    return $validator;
  }

  public function validationLogin($validator){

    $validator
    ->notEmpty('email', 'Ingrese el email')
    ->add('email','valid',[
      'rule'=>'email',
      'message'=>'Ingresa un email valido'
    ])
    ->notEmpty('password', 'Ingrese la contraseña');

    return $validator;
  }

}
