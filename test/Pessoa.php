<?php
use src\ActiveRecord;

class Pessoa extends ActiveRecord
{

    public function __construct($attributes = [])
    {
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