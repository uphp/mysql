<?php
namespace src\traits;

trait ActiveRecordFinderMethods
{
    public static function find($sql)
    {
        //$obj = static::getInstance();
        self::getConn();
        $pre = self::$db->prepare($sql);
        $pre->execute();
        return $pre->fetch();
        //$stmt = $conn->db->prepare($str);
        //return $stmt->execute()->fetch();
    }
}