<?php

class PsrLinterTest
{
    private $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function __getDDDD()
    {
    }

    public function getName()
    {
        return $this->arg;
    }
}

