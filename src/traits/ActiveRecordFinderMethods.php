<?php
namespace src\traits;

use Exception;

trait ActiveRecordFinderMethods
{
    public $select = '*';
    public $where;
    public $andWhere;
    public $orWhere;
    public $limit;
    public $orderBy;
    public $groupBy;

    public static function find($type = NULL)
    {
        if (empty($type)) return static::getInstance();
        self::getConn();
        $pre = self::$db->prepare($type);
        $pre->execute();
        return $pre->fetch();
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

    public function select($type = NULL)
    {
        return $this->validationType($type, 'select');
    }

    public function where($type = NULL)
    {
        return $this->validationType($type, 'where');
    }

    public function andWhere($type = NULL)
    {
        return $this->validationType($type, 'andWhere');
    }

    public function orWhere($type = NULL)
    {
        return $this->validationType($type, 'orWhere');
    }

    public function limit($type = NULL)
    {
        return $this->validationType($type, 'limit');
    }

    public function orderBy($type = NULL)
    {
        return $this->validationType($type, 'orderBy');
    }

    public function groupBy($type = NULL)
    {
        return $this->validationType($type, 'groupBy');
    }

    private function validationType($type = NULL, $property)
    {
        if (is_array($type)) {
            if (empty($this->$property)) $this->$property = $type;
            else $this->$property = array_merge($this->$property, $type);
            return $this;
        } elseif (is_string($type)) {
            $this->$property = $type;
            return $this;
        } else throw new Exception('Não foi possível reconhecer o tipo do parametro.');
    }

    public function all()
    {
        //
    }

    public function one()
    {
        //
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