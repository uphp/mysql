<?php
namespace src;

use Exception;

require_once "traits/ActiveRecordConnection.php";
require_once "traits/ActiveRecordPersistence.php";
require_once "traits/ActiveRecordFinderMethods.php";
require_once "traits/ActiveRecordPrivateMethods.php";

abstract class ActiveRecord
{

    use \src\traits\ActiveRecordConnection;
    use \src\traits\ActiveRecordPrivateMethods;
    use \src\traits\ActiveRecordPersistence;
    use \src\traits\ActiveRecordFinderMethods;

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