<?php

namespace hexletPsrLinter;

class PsrLinter
{
    private $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function GetName()
    {
        return $this->arg;
    }
}
