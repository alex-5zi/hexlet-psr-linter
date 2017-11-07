<?php

class myClass
{
    protected $name;
    protected $FAILname;
    protected $name1Name1;
    public function __construct($name = 'root', $FAILname = 'root')
    {
        $this->name = $name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $name;
    }
    public function SetFailName($_fail_name)
    {
        $this->FAILname = $_fail_name;
    }
    public function get_failName($name)
    {
        $this->FAILname;
    }
}