<?php
namespace src\traits;

trait ActiveRecordPersistence
{
    private static $for_update = FALSE;
    protected static $table    = NULL;
    private static $connection = NULL;

    /* BEGIN Manipulation Functions ***********************************************/

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    protected function save()
    {
        if ($this->for_update) return $this->update();
        return $this->create();
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    protected function update()
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

    protected static function create(Array $object_array)
    {
        self::connect();

        // BEGIN MODELO EXEMPLO
        # public function create($objeto = stdClass)
        $this->resgatarTabela($objeto);
        $this->getSyntaxCreate($objeto);
        $this->execute(__FUNCTION__);
        // END EXEMPLO
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    protected function delete(){
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