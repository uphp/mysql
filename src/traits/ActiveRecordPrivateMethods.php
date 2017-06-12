<?php
namespace src\traits;

trait ActiveRecordPrivateMethods
{
    private static function getClassName()
    {
        $instance_class = get_called_class(); // recebe a string da class instanciada juntamente com o namespace
        $classArray = explode("\\", $instance_class);
        return end($classArray); // recebe o nome da class
    }

    // EXEMPLO DE MODELOS DAS FUNCTIONS -------------------------------------------/
    public function setTabela($tabela) {
        $this->tabela = strtolower((String) $tabela);
    }

    public function getTabela() {
        return $this->tabela;
    }

    public function getResult() {
        return $this->result;
    }
    
    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    //Resgatar Tabela
    private function resgatarTabela($objeto = stdClass){
        if (empty($objeto->getTabela())):
            $this->tabela = strtolower(Inflection::Pluralize(get_class($objeto)));
        endif;
    }

    //Cria a sintaxe da query para Prepared Statements
    private function getSyntaxCreate($objeto = stdClass) {
        $array = (array) $objeto;
        foreach ($array as $key => $value):
            if (substr($key, 1, strlen(get_class($objeto))) == get_class($objeto) and substr($key, 2 + strlen(get_class($objeto))) != 'id'):
                $fields[] = substr($key, 2 + strlen(get_class($objeto)));
                $places[] = substr($key, 2 + strlen(get_class($objeto)));
                $this->dados[substr($key, 2 + strlen(get_class($objeto)))] = $value;
            endif;
        endforeach;

        $fields = implode(', ', array_values($fields));
        $places = ':' . implode(', :', array_values($places));
        $this->status = "INSERT INTO {$objeto->getTabela()} ({$fields}) VALUES ({$places})";
    }

    //Cria a sintaxe da query para Prepared Statements
    private function getSyntaxUpdate($objeto = stdClass) {
        foreach ($this->dados as $key => $value):
            $places[] = $key . ' = :' . $key;
        endforeach;
        $this->dados = array_merge($this->dados, ['id' => $objeto->getId()]);
        $places = implode(', ', $places);
        $this->status = "UPDATE {$this->tabela} SET {$places} WHERE id = :id";
    }

    //Cria a sintaxe da query para Prepared Statements
    private function getSyntaxDelete($objeto) {
        $this->dados['id'] = $objeto->getId();
        $this->status = "DELETE FROM {$objeto->getTabela()} WHERE id=:id";
    }

    //Cria a sintaxe da query para Prepared Statements
    private function getSyntaxFind() {
        if (!empty($this->dados)):
            foreach ($this->dados as $vinculo => $valor):
                if ($vinculo == 'limit' || $vinculo == 'offset'):
                    $valor = (int) $valor;
                endif;
                $this->status->bindValue(":{$vinculo}", $valor, ( is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            endforeach;
        endif;
    }

    //Obtém o PDO e Prepara a query
    //private function connect($func = null) {
    //    $this->conn = parent::getConn();
    //    ($func == 'find' || $func == 'findSQL') ? $this->status = $this->conn->prepare($this->query) : $this->status = $this->conn->prepare($this->status);
    //}
    
    //Obtém a Conexão e a Syntax, executa a query!
    //private function execute($operacao) {
    //    $this->connect($operacao);
    //    try {
    //        if ($operacao == 'find' || $operacao == 'findSQL'):
    //            $this->status->setFetchMode(PDO::FETCH_ASSOC);
    //            $this->getSyntaxFind();
    //            $this->status->execute();
    //        else:
    //            $this->status->execute($this->dados);
    //        endif;
    //        $this->setResult($operacao);

    //    } catch (PDOException $e) {
    //        $this->result = null;
    //        WSErro("<b>Erro ao cadastrar:</b> {$e->getMessage()}", $e->getCode());
    //    }
    //}

    private function setResult($operacao) {
        if ($operacao == "create"):
            $this->result = $this->conn->lastInsertId();
        elseif ($operacao == "delete"):
            $this->result = true;
        elseif ($operacao == "find" || $operacao == 'findSQL'):
            ($this->status->rowCount() == 1) ? $this->result = $this->status->fetch() : $this->result = $this->status->fetchAll();
        elseif($operacao == 'update'):
            $this->result = true;
        endif;
    }
}