<?php
namespace UPhp\Model;

class ActiveQuery extends ActiveRecord
{
    private $class;

    public function __construct($class)
    {
        parent::__construct();
        $this->class = $class;
    }

    public function select($type = NULL)
    {
        return $this->validationType($type, __FUNCTION__);
    }

    public function where($args = NULL)
    {
        return $this->validationType($args, __FUNCTION__);
    }

    public function andWhere($args = NULL)
    {
        return $this->validationType($args, __FUNCTION__);
    }

    public function orWhere($args = NULL)
    {
        return $this->validationType($args, __FUNCTION__);
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

    // PRIVATE
    private function validationType($args = NULL, $property)
    {
        if (is_array($args)) {
            if (empty($this->$property)) $this->$property = $args;
            else $this->$property = array_merge($this->$property, $args);
            return $this;
        } elseif (is_string($args)) {
            $this->$property = $args;
            return $this;
        } else throw new Exception('Não foi possível reconhecer o tipo do parametro.');
    }
}