<?php
namespace src\traits;

use PDO;

trait ActiveRecordPrivateMethods
{
    private $oldValues = [];
    //private $sql;
    private $result;

    private static function getInstance()
    {
        require_once (__DIR__ . "/../test/" . get_called_class() . ".php");
        $class = ucfirst(static::getClassName());
        return (empty(self::$instance)) ? self::$instance = new $class() : self::$instance;
    }

    private static function getClassName()
    {
        $instance_class = get_called_class(); // recebe a string da class instanciada juntamente com o namespace
        $classArray = explode("\\", $instance_class);
        return end($classArray); // recebe o nome da class
    }

    private function getSyntaxCreate() 
    {
        foreach ($this->attributes() as $key => $label) {
            if ($this->auto_increment && $key === $this->primary_key) continue;
            if (! isset($this->$key)) continue;
            $arrFields[$key] = ':' . $key;
            $this->oldValues[$key] = $this->$key;
        }

        if (! empty($this->time_stamp)) {
            $arrFields['created_at'] = ':created_at';
            $this->oldValues['created_at'] = date('Y-m-d H:i:s');
            $arrFields['updated_at'] = ':updated_at';
            $this->oldValues['updated_at'] = date('Y-m-d H:i:s');
            $arrFields['deleted_at'] = ':deleted_at';
            $this->oldValues['deleted_at'] = NULL;
        }

        $fields = implode(', ', array_keys($arrFields));
        $places = implode(', ', array_values($arrFields));
        $table = $this->table;
        $this->execute("INSERT INTO {$table} ({$fields}) VALUES ({$places})", __FUNCTION__);
    }

    private function execute($sql, $operation)
    {
        try {
            self::$db->beginTransaction();
            $stmt = self::$db->prepare($sql);
            foreach ($this->oldValues as $key => $value) {
                switch (gettype($value)) {
                    case 'boolean':
                        $typeParam = PDO::PARAM_BOOL;
                        break;
                    case 'integer':
                        $typeParam = PDO::PARAM_INT;
                        break;
                    case 'double':
                        $typeParam = PDO::PARAM_STR;
                        break;
                    case 'string':
                        $typeParam = PDO::PARAM_STR;
                        break;
                    case 'NULL':
                        $typeParam = PDO::PARAM_NULL;
                        break;
                    default:
                        throw new Exception('Não conseguimos identificar o tipo passado.');
                }
                $stmt->bindValue(':' . $key, $value, $typeParam);
            }
            $stmt->execute();
            $this->setResult($operation);
            self::$db->commit();
            return TRUE;
        } catch (PDOException $e) {
            if (stripos($e->getMessage(), 'DATABASE IS LOCKED') !== false) {
                // This should be specific to SQLite, sleep for 0.25 seconds
                // and try again.  We do have to commit the open transaction first though
                self::$db->commit();
                usleep(250000);
            } else {
                $this->result = NULL;
                self::$db->rollBack();
                throw $e;
            }
        }
    }

    private function setResult($operation)
    {
        if ($operation == "getSyntaxCreate") {
            $this->result = self::$db->lastInsertId();
        } elseif ($operation == "getSyntaxDelete") {
            $this->result = true;
        } elseif ($operation == "getSyntaxFind" || $operation == "getSyntaxFindOne" || $operation == "getSyntaxFindAll") {
            (self::$db->rowCount() == 1) ? $this->result = self::$db->fetch() : $this->result = self::$db->fetchAll();
        } elseif ($operation == 'getSyntaxUpdate') {
            $this->result = true;
        }
    }

    // EXEMPLO DE MODELOS DAS FUNCTIONS -------------------------------------------/
    /*public function setTabela($tabela) {
        $this->tabela = strtolower((String) $tabela);
    }

    public function getTabela() {
        return $this->tabela;
    }

    public function getResult() {
        return $this->result;
    }*/
    
    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    //Resgatar Tabela
    /*private function resgatarTabela($objeto = stdClass){
        if (empty($objeto->getTabela())):
            $this->tabela = strtolower(Inflection::Pluralize(get_class($objeto)));
        endif;
    }*/

    //Cria a sintaxe da query para Prepared Statements
    /*private function getSyntaxCreate($objeto = stdClass) {
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
    }*/

    //Cria a sintaxe da query para Prepared Statements
    /*private function getSyntaxUpdate($objeto = stdClass) {
        foreach ($this->dados as $key => $value):
            $places[] = $key . ' = :' . $key;
        endforeach;
        $this->dados = array_merge($this->dados, ['id' => $objeto->getId()]);
        $places = implode(', ', $places);
        $this->status = "UPDATE {$this->tabela} SET {$places} WHERE id = :id";
    }*/

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
}