<?php
namespace src;

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

    // PRIVATE
    private function validationType($type = NULL, $property)
    {
        if (is_array($type)) {
            if (empty($this->$property)) $this->$property = $type;
            else $this->$property = array_merge($this->$property, $type);
            return $this;
        } elseif (is_string($type)) {
            $this->$property = $type;
            return $this;
        } else throw new Exception('Não foi possível reconhecer o tipo do parametro.');
    }
}