<?php
namespace src\traits;

trait ActiveRecordPersistence
{

    private $for_update  = FALSE;
    protected $table      = NULL;
    private $connection = NULL;

    /* BEGIN Manipulation Functions ***********************************************/

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    protected function save(){
        if($this->for_update){
            return $this->update();
        }else{
            // Codigo aqui    
        }
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    protected function update(){
        // Codigo aqui
        $this->connect();
        return $this;
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    protected function delete(){
        // Codigo aqui
        $this->connect();
        return $this;
    }

    protected static function create(Array $object_array){
        // Codigo aqui
        $this->connect();
    }
    /* END Manipulation Functions *************************************************/

}