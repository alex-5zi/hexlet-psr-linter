<?php

namespace hexletPsrLinter;

use org\bovigo\vfs\vfsStream;
use hexletPsrLinter\Logger\Logger;

class PsrLinterTest extends \PHPUnit_Framework_TestCase
{
    
    public function testPsrLint()
    {
        $logger = new Logger();
        $logger->warning(
            "Method name is not in camel caps format",
            [
                                        'line' => 1,
                                        'name' => "DconsDtruct"
                                    ]
        );
        
        $logger2 = new Logger();
        $logger2->warning(
            "A file SHOULD declare new symbols
                                (classes, functions, constants, etc.)
                                and cause no other side effects, or
                                it SHOULD execute logic with
                                side effects, but SHOULD NOT do both."
        );
        
        $root = vfsStream::setup('home');
        vfsStream::newFile('test.php')->at($root)
                                      ->setContent('<?php class PsrLinterTest { public function DconsDtruct(){}}');
        
        vfsStream::newFile('test2.php')->at($root)
                                       ->setContent('<?php
                                                    include "file.php";
                                                    class PsrLinterTest
                                                    { public function getName(){} }');
        
        $this->assertEquals(0, count(psrLint()));
        
        $result = ['vfs://home/test.php' => $logger->getLog()];
        
        $this->assertEquals($result, psrLint(vfsStream::url('home/test.php')));
        
        $result = ['vfs://home/test.php' => $logger->getLog(),
                   'vfs://home/test2.php' => $logger2->getLog()];
        
        $paths = array(vfsStream::url('home/test.php'),
                       vfsStream::url('home/test2.php'),
                       );
        
        $this->assertEquals($result, psrLint($paths));
    }
}
