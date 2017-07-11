<?php
require("../vendor/autoload.php");
require ("Pessoa.php");

var_dump(new Pessoa);
/*var_dump(
    Pessoa::find()
        ->select('*')
        ->where(['teste' => 'test'])
        ->andWhere(['nome' => 'renan'])
        ->andWhere(['sexo' => 'm'])
        ->andWhere(['nascimento', '>=', '2000-01-01'])
        ->orWhere("sobrenome = 'valente'")
        ->limit('1')
        ->orderBy('nome')
        ->groupBy('sobrenome')
    );die;*/
//$pessoas = Pessoa::find()->db->prepare('SELECT * FROM pessoas')->execute()->fetchAll(PDO::FETCH_OBJ);

var_dump(Pessoa::findOne(['token' => '123', 'nome' => ['Renan Valente', 'almeida', 'renan']]));
var_dump(Pessoa::findOne(1741));die;

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

