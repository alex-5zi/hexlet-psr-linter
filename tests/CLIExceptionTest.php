<?php

namespace hexletPsrLinter;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use hexletPsrLinter\Exceptions\CLIException;
use hexletPsrLinter\CLI;

class CLIExceptionTest extends TestCase
{
    /**
    * @expectedException hexletPsrLinter\Exceptions\CLIException
    */
    public function testCLIException()
    {
        $class = new \ReflectionClass('hexletPsrLinter\CLI');
        $method = $class->getMethod('processArgument');
        $method->setAccessible(true);
        $args = $class->getProperty('args');
        $args->setAccessible(true);
        $obj = new CLI();
        $args->setValue($obj, array("-f", "--rules", vfsStream::url('root/Core/Rules'),
        vfsStream::url('root/Core/src')));
        $method->invoke($obj, 'h', 0);
    }
}
