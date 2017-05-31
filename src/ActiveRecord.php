<?php
namespace src;

abstract class ActiveRecord {

    use \src\traits\ActiveRecordConnection;
    use \src\traits\ActiveRecordPrivateMethods;
    use \src\traits\ActiveRecordPersistence;
    use \src\traits\ActiveRecordFinderMethods;

    public function __construct()
    {
        $var = require("connection.php");
        $this->connect($var);

        $this->table = Inflection::pluralize($this->getClassName());
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