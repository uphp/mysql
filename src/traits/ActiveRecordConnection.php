<?php
namespace src\traits;

trait ActiveRecordConnection{

    private $pk_field        = "_id";

    // Local onde fazemos a conexão com o banco de dados
    private function connectDB($dns, $username, $password, $options){
        /*if (class_exists(PDOConnection::class) && ! $this->isPersistentConnection($options)) {
            return new PDOConnection($dsn, $username, $password, $options);
        }*/

        return new PDO($dsn, $username, $password, $options);
    }

    // Tratamento dos errors
    private function errorDatabase($file = NULL, $func = NULL, $msgerror = NULL){
        // Utilizando os Exceptions
    }

    // Function onde o framework pega a conexão com o banco
    private function connect($db){
        $dns = $this->getDNS($db);
        $this->connectDB($dns, $db["username"], $db["password"]);
    }

    private function getDNS($db)
    {
        return $db["driver"] . ':host=' . $db["host"] . ':' . $db["port"] . ';dbname=' . $db["database"];
    }

}