<?php
namespace src\traits;

use Exception;
use src\ActiveQuery;

trait ActiveRecordFinderMethods
{
    public static function find($where = NULL)
    {
        if (empty($where)) return new ActiveQuery(get_class(static::getInstance()));
        self::getConn();
        $pre = self::$db->prepare($where);
        $pre->execute();
        return $pre->fetch();
        
        
        /*if (is_array($where)) {
            if (is_array($where) && isset($this->where) && is_array($this->where)) {
                $where = array_merge($where, $this->where);
            }
            if (is_array($where) && isset($this->andWhere) && is_array($this->andWhere)) {
                $where = array_merge($where, $this->andWhere);
            }
            $orWhere = (isset($this->orWhere) && is_array($this->orWhere)) ? $this->orWhere : [];
        } else {
            $where = [$this->primary_key => $where];
        }*/
        
        
        //$stmt = $conn->db->prepare($str);
        //return $stmt->execute()->fetch();

        // BEGIN MODELO EXEMPLO
        # public function find($objeto = stdClass, $termos = null, array $dados = [])
        /*$this->resgatarTabela($objeto);
        $this->dados = $dados;
        $this->query = "SELECT * FROM {$objeto->getTabela()} {$termos}";
        $this->execute(__FUNCTION__);*/
        // END MODELO EXEMPLO
    }

    public static function findOne($where = [])
    {
        self::getConn();
        $instance = static::getInstance();

        if (! is_array($where)) {
            $where = [$instance->primary_key => $where];
        }

        $instance->getSyntaxFindOne(['and' => $where]);

        if (! empty($instance->result)) {
            $obj = $instance->result;
            $instance->result = TRUE;
            return $obj;
        } else return FALSE;
    }

    // EXEMPLO DE MODELOS DAS FUNCTIONS -------------------------------------------/
    /* ********** FIND SQL *********** */
    public function findSQL($query, $parseString = null) {
        $this->query = (string) $query;
        if (!empty($parseString)):
            parse_str($parseString, $this->dados);
        endif;
        $this->execute(__FUNCTION__);
    }
}