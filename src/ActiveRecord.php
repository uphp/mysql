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
        if (empty(static::$table)) static::$table = strtolower(Inflection::pluralize($this->getClassName()));
        if (!empty($datas)) {
            $datas = (array) $datas;
            foreach ($datas as $key => $value) {
                if (array_key_exists($key, $this->attributes())) $this->$key = $value;
            }
        }

        if (empty(static::$auto_increment) && empty(static::$primary_key_value)) {
            static::$primary_key_value = sha1(uniqid(rand(), TRUE));
            $pk = static::$primary_key;
            $this->$pk = static::$primary_key_value;
        } elseif (empty(static::$auto_increment) && !empty(static::$primary_key_value)) {
            $pk = static::$primary_key;
            $this->$pk = static::$primary_key_value;
        } else static::$primary_key_value = NULL;
        var_dump($this);
        var_dump(static::$primary_key_value);
        var_dump(static::$auto_increment);
    }

    private static function getInstance()
    {
        require_once (__DIR__ . "/../test/" . get_called_class() . ".php");
        $class = ucfirst(static::getClassName());
        return (empty(self::$instance)) ? self::$instance = new $class() : self::$instance;
    }

    public function __get($name)
    {
        throw new Exception("Property $name cannot be read");
    }

    /*public function __set($name, $value)
    {
        throw new Exception("Property $name cannot be set");
    }*/

    public function setAttributes($attributes = NULL)
    {
        $class = get_class($this);
        return (empty($attributes)) ? new $class($this) : new $class($attributes);
    }
}