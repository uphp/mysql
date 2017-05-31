<?php
    namespace src\traits;

    trait ActiveRecordPersistence{

        private $for_update  = FALSE;
        protected $table      = NULL;
        private $connection = NULL;

        /* BEGIN Manipulation Functions ***********************************************/

        // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
        public function save(){
            if($this->for_update){
                return $this->update();
            }else{
                // Codigo aqui    
            }
        }

        // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
        public function update(){
            // Codigo aqui
            return $this;
        }

        // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
        public function delete(){
            // Codigo aqui
            return $this;
        }

        public static function create(Array $object_array){
            // Codigo aqui
        }
        /* END Manipulation Functions *************************************************/

    }