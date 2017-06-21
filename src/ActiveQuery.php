<?php
namespace src;

class ActiveQuery extends ActiveRecord
{
    private static $class;

    public function __construct($class)
    {
        parent::__construct();
        self::$class = $class;
    }

    public function select($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function where($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function andWhere($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function orWhere($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function limit($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function orderBy($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function groupBy($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function all()
    {
        //
    }

    public function one()
    {
        //
    }
}