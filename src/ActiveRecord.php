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

    public function __construct()
    {
        if (empty(static::$table)) static::$table = Inflection::pluralize($this->getClassName());
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

    public function __set($name, $value)
    {
        throw new Exception("Property $name cannot be set");
    }
}