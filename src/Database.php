<?php
namespace UPhp\Model;

use Exception;
use src\Inflection;

class Database
{

    use traits\ActiveRecordConnection;
    use traits\ActiveRecordPrivateMethods;
    use traits\ActiveRecordPersistence;
    use traits\ActiveRecordFinderMethods;

    private static $instance;

    public function __construct($datas = [])
    {
        if (empty($this->table)) $this->table = strtolower(Inflection::pluralize($this->getClassName()));

        if (!empty($datas)) {
            $datas = (array) $datas;
            foreach ($datas as $key => $value) {
                if (array_key_exists($key, $this->attributes())) $this->$key = $value;
            }
        }

        if (empty($this->auto_increment) && empty($this->primary_key_value)) {
            $this->primary_key_value = sha1(uniqid(rand(), TRUE));
            $pk = $this->primary_key;
            $this->$pk = $this->primary_key_value;
        } elseif (empty($this->auto_increment) && !empty($this->primary_key_value)) {
            $pk = $this->primary_key;
            $this->$pk = $this->primary_key_value;
        }
    }

    public function __get($name)
    {
        throw new Exception("Property $name cannot be read");
    }

    /*public function __set($name, $value)
    {
        throw new Exception("Property $name cannot be set");
    }*/

    public static function descDatabase()
    {
        self::getConn();
        $pre = self::$db->prepare("show tables;");
        $pre->execute();

        //$resultArr = [];
        $table = "";
        $arrColumns = [];
        while ($obj = $pre->fetchObject()) {
            $table = key($obj);
            $desc = self::$db->prepare("desc " . $obj->$table);
            $desc->execute();
            while ($column = $desc->fetchObject()) {
                $arrColumns[$obj->$table][] = $column->Field;
            }
            //$resultArr[] = $obj;
        }
        $base = strrchr($table, '_'); $base = str_replace('_', '', $base);
        //var_dump($base);
        return [$base => $arrColumns];
        //var_dump($arrColumns);
        //self::$db = NULL;
    }

    public function setAttributes($datas)
    {
        $datas = (array) $datas;
        $attrs = $this->attributes();
        foreach ($attrs as $attr => $label) {
            if (isset($datas[$attr])) $this->$attr = $datas[$attr];
        }
    }

    public function oldValues()
    {
        return $this->oldValues;
    }
}