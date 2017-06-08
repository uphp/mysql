<?php
namespace test;

require("../vendor/autoload.php");
require_once "Pessoa.php";

$pessoas = Pessoa::find()->db->prepare('SELECT * FROM pessoas')->execute()->fetchAll(PDO::FETCH_OBJ);

var_dump($pessoas);
