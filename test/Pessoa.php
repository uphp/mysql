<?php
use src\ActiveRecord;

class Pessoa extends ActiveRecord
{
    protected static $auto_increment;
    //protected static $primary_key_value;

    public function __construct($attributes = [])
    {
        //self::$primary_key_value = date('YmdHis');
        parent::__construct($attributes);
    }

    public function attributes()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'nome' => 'Nome',
            'email' => 'Email',
            'tel_residencial' => 'Tel. Residencial',
            'sexo' => 'Sexo',
            'cpf' => 'CPF'
        ];
    }
}