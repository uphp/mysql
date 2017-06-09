<?php
require("../vendor/autoload.php");
require ("Pessoa.php");

var_dump(Pessoa::find('SELECT * FROM pessoas'));die;
//$pessoas = Pessoa::find()->db->prepare('SELECT * FROM pessoas')->execute()->fetchAll(PDO::FETCH_OBJ);

var_dump($pessoas);
