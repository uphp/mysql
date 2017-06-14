<?php
require("../vendor/autoload.php");
require ("Pessoa.php");

var_dump(
    Pessoa::find()
        ->select('*')
        ->where(['teste' => 'test'])
        ->andWhere(['nome' => 'renan'])
        ->andWhere(['sexo' => 'm'])
        ->orWhere("or (sobrenome = 'valente')")
        ->limit('1')
        ->orderBy('nome')
        ->groupBy('sobrenome')
    );die;
//$pessoas = Pessoa::find()->db->prepare('SELECT * FROM pessoas')->execute()->fetchAll(PDO::FETCH_OBJ);

var_dump($pessoas);
