<?php
namespace src\traits;

trait ActiveRecordPersistence
{
    private static $for_update = FALSE;
    private static $connection = NULL;
    public static $table = NULL;
    public $timeStamp = TRUE;

    /* BEGIN Manipulation Functions ***********************************************/

    public function create()
    {
        self::connect();
        $this->getSyntaxCreate();
        var_dump($this);
        var_dump(self::$sql);
        die;

        // BEGIN MODELO EXEMPLO
        # public function create($objeto = stdClass)
        $this->resgatarTabela($objeto);
        $this->getSyntaxCreate($objeto);
        $this->execute(__FUNCTION__);
        // END EXEMPLO
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    public function update(Array $object_array = [])
    {
        // Codigo aqui
        self::connect();
        return $this;

        // BEGIN MODELO EXEMPLO
        # public function update(array $dados, $objeto = stdClass)
        $this->resgatarTabela($objeto);
        $this->dados = $dados;
        $this->getSyntaxUpdate($objeto);
        $this->execute(__FUNCTION__);
        // END EXEMPLO
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    public function save()
    {
        if ($this->for_update) return $this->update();
        return $this->create();
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    public function delete(){
        // Codigo aqui
        self::connect();
        return $this;

        // BEGIN MODELO EXEMPLO
        # public function delete($objeto = stdClass)
        $this->resgatarTabela($objeto);
        $this->getSyntaxDelete($objeto);
        $this->execute(__FUNCTION__);
        // END EXEMPLO
    }
    /* END Manipulation Functions *************************************************/
}