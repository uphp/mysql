<?php
namespace src\traits;

use Exception;
use src\ActiveQuery;

trait ActiveRecordFinderMethods
{
    public static function find($type = NULL)
    {
        if (empty($type)) return new ActiveQuery(get_class(static::getInstance()));
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