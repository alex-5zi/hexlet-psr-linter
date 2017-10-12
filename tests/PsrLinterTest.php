<?php

namespace hexletPsrLinter;

use PHPUnit\Framework\TestCase;

class PsrLinterTest extends TestCase
{
    
    public function testPsrLint()
    {
        $result = 'hexlet-psr-linter';
        $this->assertEquals($result, psrLint('hexlet-psr-linter'));
    }
}
