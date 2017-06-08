<?php
namespace test;

use src\ActiveRecord;

class Pessoa extends ActiveRecord
{
    public function __construct()
    {
        parent::__construct();
    }
}