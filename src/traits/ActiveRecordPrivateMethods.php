<?php
namespace UPhp\Model\traits;

use PDO;

trait ActiveRecordPrivateMethods
{
    private $oldValues = [];
    //private $sql;
    private $result;

    private static function getInstance()
    {
        $class = static::getClassName();
        require_once (__DIR__ . "/../../test/" . $class . ".php");
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
        $this->execute("INSERT INTO {$this->table} ({$fields}) VALUES ({$places})", __FUNCTION__);
    }

    private function getSyntaxFindOne($args = NULL)
    {
        $oWhere['and'] = $this->organizeWhere($args['and']);
        $where = $this->serilizeWhere($oWhere);
        $arrValues = $oWhere['and']['arrPlaces'];
        $this->execute("SELECT * FROM {$this->table} WHERE {$where} LIMIT 1", __FUNCTION__, $arrValues);
    }

    private function getSyntaxUpdate() {
        foreach ($this->oldValues as $key => $value) {
            if (! empty($this->time_stamp) && ($key === 'created_at' || $key === 'updated_at')) continue;
            $arrFields[] = $key . ' = :' . $key;
        }

        //$pk = $this->primary_key;
        //$this->dados = array_merge($this->dados, [$pk => $this->$pk]);

        $places = implode(', ', $arrFields);
        $this->execute("UPDATE {$this->tabela} SET {$places} WHERE {$this->primary_key} = :{$this->primary_key}", __FUNCTION__);
    }

    private function execute($sql, $operation, $arrPlaces = [])
    {
        try {
            self::$db->beginTransaction();
            $stmt = self::$db->prepare($sql);
            if (empty($arrPlaces)) $arrPlaces = $this->oldValues;
            foreach ($arrPlaces as $key => $value) {
                if (strstr($sql, ':' . $key) === FALSE) continue;
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
            $this->setResult($operation, $stmt);
            self::$db->commit();
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

    private function setResult($operation, $stmt = NULL)
    {
        if ($operation === "getSyntaxCreate") {
            if ($this->auto_increment) {
                $this->result = self::$db->lastInsertId();
            } else $this->result = TRUE;
            $this->for_update = TRUE;
        } elseif ($operation === "getSyntaxDelete" || $operation === "getSyntaxUpdate") {
            $this->result = TRUE;
        } elseif ($operation === "getSyntaxFind" || $operation === "getSyntaxFindOne" || $operation === "getSyntaxFindAll") {
            $class = (get_class($this) === 'ActiveQuery') ? $this->class : get_class($this);
            if ($stmt->rowCount() == 1) {
                $obj = $stmt->fetchObject($class);
                $obj->for_update = TRUE;
                $this->result = $obj;
            } else {
                $resultArr = [];
                while ($obj = $stmt->fetchObject($class)) {
                    $obj->for_update = TRUE;
                    $resultArr[] = $obj;
                }
                $this->result = $resultArr;
            }
        }
    }

    private function organizeWhere($where)
    {
        /**
         * Formas de se receber um array
         * ex.: ['key' => 'value', 'key' => ['value', 'value']] || ['key', 'condition', 'value']
         */
        $arrPlaces = [];
        if (! empty($where) && is_array($where)) {
            $countKey = 0;
            $strKey = '';
            foreach ($where as $key => $value) {
                if (is_int($key)) {
                    if ($countKey === 0) {
                        $strKey = $value;
                        $countKey ++;
                    } elseif ($countKey === 1) {
                        $strKey .= ' ' . $value;
                        $countKey ++;
                    } elseif ($countKey === 2) {
                        $arrStrKey = explode(' ', $strKey);
                        $arrPlaces[reset($arrStrKey)] = $value;
                        $arrFields[$strKey] = ':' . reset($arrStrKey);;
                        $countKey = 0;
                        $strKey = '';
                    }
                } elseif (is_string($key)) {
                    if (is_array($value)) {
                        $arrValues = [];
                        foreach ($value as $k => $val) {
                            if (! is_int($k)) breack;
                            //($place === '') ? $place = $key . $k : $place .= $k;
                            $place = $key . $k;
                            $arrValues[$place] = $val;
                        }
                        $strPlace = '';
                        foreach ($arrValues as $place => $val) {
                            $arrPlaces[$place] = $val;
                            if ($strPlace === '') $strPlace = ':' . $place;
                            else $strPlace .= ', :' . $place;
                        }
                        $arrFields[$key . ' IN ('] = $strPlace . ')';
                    } elseif (is_string($value) || is_int($value)) {
                        $arrPlaces[$key] = $value;
                        $arrFields[$key . ' = '] = ':' . $key;
                    } else {
                        break;
                    }
                } else {
                    break;
                }
            }
            var_dump(compact('arrFields', 'arrPlaces'));
            return compact('arrFields', 'arrPlaces');
        } /*elseif (!empty($where)) {
            return $where;
        } elseif (empty($where)) return;*/
        throw new Exception('Nao conseguimos identificar todos os argumentos informados.');
    }

    private function serilizeWhere($wheres)
    {
        $strWhere = '(';
        foreach ($wheres as $key => $where) {
            if ($key == 'and') {
                foreach ($where['arrFields'] as $column => $place) {
                    if ($strWhere === '(') $strWhere .= '(' . $column . $place;
                    else $strWhere .= ' AND ' . $column . $place;
                }
                if ($strWhere !== '(') $strWhere .= ')';
            } elseif ($key == 'or') {
                $first = TRUE;
                foreach ($where['arrFields'] as $column => $place) {
                    if ($first) {
                        $strWhere .= '(' . $column . $place;
                        $first = FALSE;
                    } else $strWhere .= ' OR ' . $column . $place;
                }
                if (! $first) $strWhere .= ')';
            }
        }
        return $strWhere .= ')';
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
    /** Resgatar Tabela
     * private function resgatarTabela($objeto = stdClass){
     *    if (empty($objeto->getTabela())):
     *        $this->tabela = strtolower(Inflection::Pluralize(get_class($objeto)));
     *    endif;
     *}
     */

    /** Cria a sintaxe da query para Prepared Statements Create
     * private function getSyntaxCreate($objeto = stdClass) {
     *    $array = (array) $objeto;
     *    foreach ($array as $key => $value):
     *        if (substr($key, 1, strlen(get_class($objeto))) == get_class($objeto) and substr($key, 2 + strlen(get_class($objeto))) != 'id'):
     *            $fields[] = substr($key, 2 + strlen(get_class($objeto)));
     *            $places[] = substr($key, 2 + strlen(get_class($objeto)));
     *            $this->dados[substr($key, 2 + strlen(get_class($objeto)))] = $value;
     *        endif;
     *    endforeach;

     *    $fields = implode(', ', array_values($fields));
     *    $places = ':' . implode(', :', array_values($places));
     *    $this->status = "INSERT INTO {$objeto->getTabela()} ({$fields}) VALUES ({$places})";
     * }
     */


    /** Cria a sintaxe da query para Prepared Statements Update
     * private function getSyntaxUpdate($objeto = stdClass) {
     *     foreach ($this->dados as $key => $value):
     *         $places[] = $key . ' = :' . $key;
     *     endforeach;
     *     $this->dados = array_merge($this->dados, ['id' => $objeto->getId()]);
     *     $places = implode(', ', $places);
     *     $this->status = "UPDATE {$this->tabela} SET {$places} WHERE id = :id";
     * }
     */


    /** Cria a sintaxe da query para Prepared Statements Delete
     * private function getSyntaxDelete($objeto) {
     *     $this->dados['id'] = $objeto->getId();
     *     $this->status = "DELETE FROM {$objeto->getTabela()} WHERE id=:id";
     * }
     */

    /** Cria a sintaxe da query para Prepared Statements Find
     * private function getSyntaxFind() {
     *     if (!empty($this->dados)):
     *         foreach ($this->dados as $vinculo => $valor):
     *             if ($vinculo == 'limit' || $vinculo == 'offset'):
     *                 $valor = (int) $valor;
     *             endif;
     *             $this->status->bindValue(":{$vinculo}", $valor, ( is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
     *         endforeach;
     *     endif;
     * }
     */

    /** Obtém o PDO e Prepara a query
     * private function connect($func = null) {
     *     $this->conn = parent::getConn();
     *     ($func == 'find' || $func == 'findSQL') ? $this->status = $this->conn->prepare($this->query) : $this->status = $this->conn->prepare($this->status);
     * }
     */

    /** Obtém a Conexão e a Syntax, executa a query!
     * private function execute($operacao) {
     *     $this->connect($operacao);
     *     try {
     *         if ($operacao == 'find' || $operacao == 'findSQL'):
     *             $this->status->setFetchMode(PDO::FETCH_ASSOC);
     *             $this->getSyntaxFind();
     *             $this->status->execute();
     *         else:
     *             $this->status->execute($this->dados);
     *         endif;
     *         $this->setResult($operacao);

     *     } catch (PDOException $e) {
     *         $this->result = null;
     *         WSErro("<b>Erro ao cadastrar:</b> {$e->getMessage()}", $e->getCode());
     *     }
     * }
     */
}