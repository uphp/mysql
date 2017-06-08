<?php
namespace src\traits;

use Exception;
use PDO;

trait ActiveRecordConnection
{

    protected $pk_field        = "_id";
    protected $db;
    private $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => TRUE];

    // Local onde fazemos a conexão com o banco de dados
    private function connectDB($dns, $username, $password, $options = [])
    {
        /*if (class_exists(PDOConnection::class) && ! $this->isPersistentConnection($options)) {
            return new PDOConnection($dsn, $username, $password, $options);
        }*/
        if (empty($this->db)) {
            try {
                $this->db = new PDO($dns, $username, $password, (empty($options)) ? $this->options : $options);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "ERROR! <br> {$e->getMessage()}";
            }
        }
    }

    // Tratamento dos errors
    private function errorDatabase($file = NULL, $func = NULL, $msgerror = NULL)
    {
        // Utilizando os Exceptions
    }

    // Function onde o framework pega a conexão com o banco
    private function connect()
    {
        $db = require("../connection.php");
        $dns = $this->getDNS($db);
        $this->connectDB($dns, $db["username"], $db["password"]);
    }

    private function getDNS($db)
    {
        return $db["driver"] . ':host=' . $db["host"] . ((!empty($db["port"])) ? (';port=' . $db["port"]) : '') . ';dbname=' . $db["database"];
    }

}