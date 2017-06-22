<?php
require("../vendor/autoload.php");
require ("Pessoa.php");

/*var_dump(
    Pessoa::find()
        ->select('*')
        ->where(['teste' => 'test'])
        ->andWhere(['nome' => 'renan'])
        ->andWhere(['sexo' => 'm'])
        ->orWhere("or (sobrenome = 'valente')")
        ->limit('1')
        ->orderBy('nome')
        ->groupBy('sobrenome')
    );die;*/
//$pessoas = Pessoa::find()->db->prepare('SELECT * FROM pessoas')->execute()->fetchAll(PDO::FETCH_OBJ);

/*$test = new Pessoa();
$test->token = '123';
$test->sexo = 'M';
$test->nome = 'Renan';
$test->CPF = '13543740709';
$pessoa = new Pessoa($test);*/

$pessoa = new Pessoa([
    'token' => '123',
    'nome' => 'Renan Valente',
    'sexo' => true,
    'cpf' => '13543740709',
    'email' => 'renan.a.valente@gmail.com',
    'tel_residencial' => '21 21379852'
]);
var_dump($pessoa);
var_dump($pessoa->create());
$pessoaB = $pessoa;
var_dump($pessoaB->oldValues());
var_dump($pessoa);

