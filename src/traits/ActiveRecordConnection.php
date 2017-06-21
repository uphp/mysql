<?php
namespace src\traits;

use Exception;
use PDO;

trait ActiveRecordConnection
{
    private static $db;
    private static $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => TRUE];

    // Function onde o framework pega a conexão com o banco
    private static function connect()
    {
        $config = require_once(__DIR__ . "\..\connection.php");
        $dns = self::getDNS($config);
        self::connectDB($dns, $config["username"], $config["password"]);
    }

    private static function getDNS($config)
    {
        return $config["driver"] . ':host=' . $config["host"] . ((!empty($config["port"])) ? (';port=' . $config["port"]) : '') . ';dbname=' . $config["database"];
    }

    // Local onde fazemos a conexão com o banco de dados
    private static function connectDB($dns, $username, $password, $options = [])
    {
        /*if (class_exists(PDOConnection::class) && ! $this->isPersistentConnection($options)) {
            return new PDOConnection($dsn, $username, $password, $options);
        }*/
        if (empty(self::$db)) {
            try {
                self::$db = new PDO($dns, $username, $password, (empty($options)) ? self::$options : $options);
            } catch (PDOException $e) {
                self::errorDatabase($e->getFile(), $e->getCode(), $e->getMessage(), $e->getLine());
                die;
            }
        }

        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$db;
    }

    // Tratamento dos errors
    private static function errorDatabase($file = NULL, $code = NULL, $msgerror = NULL, $line = NULL)
    {
        // Utilizando os Exceptions
    }

    protected static function getConn()
    {
        return self::connect();
    }
}