<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    use Cake\Validation\Validator;
    class ServiciosTable extends Table{
        
        public function initialize(array $config){
            $this->addBehavior('Timestamp');
            $this->addBehavior('Josegonzalez/Upload.Upload', [
                'imagen' => [
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
        
        public function validationDefault(Validator $validator){
            $validator
              ->notEmpty('nombre_es')
              ->notEmpty('nombre_en')
              ->notEmpty('descripcion_es')
              ->notEmpty('descripcion_en')
              ->requirePresence(['nombre_es','nombre_en','descripcion_es','descripcion_en']);
        
            return $validator;
        }
        
        public function validationUpdate($validator){
        
            $validator
              ->notEmpty('nombre_es', 'El nombre español no puede quedar vacío')
              ->notEmpty('nombre_en', 'El nombre ingles no puede quedar vacío')
              ->notEmpty('descripcion_es', 'La descripcion español no puede quedar vacía')
              ->notEmpty('descripcion_en', 'La descripcion ingles no puede quedar vacía');
        
            return $validator;
        }
    }
