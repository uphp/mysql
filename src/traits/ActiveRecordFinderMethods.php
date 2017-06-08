<?php
namespace src\traits;

trait ActiveRecordFinderMethods
{
    public static function find()
    {
        return static::getInstance();
    }
}