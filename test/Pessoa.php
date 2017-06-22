<?php
use src\ActiveRecord;

class Pessoa extends ActiveRecord
{
    //protected $auto_increment;
    //protected $primary_key_value;

    public function __construct($attributes = [])
    {
        //$this->primary_key_value = date('YmdHis');
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
            'cpf' => 'CPF',
            'created_at' => 'Data de criação',
            'updated_at' => 'Data de atualização',
            'deleted_at' => 'Data de deleção',
        ];
    }
}