<?php
namespace src\traits;

trait ActiveRecordPrivateMethods
{
    private static function getClassName()
    {
        $instance_class = get_called_class(); // recebe a string da class instanciada juntamente com o namespace
        $classArray = explode("\\", $instance_class);
        return end($classArray); // recebe o nome da class
    }
}