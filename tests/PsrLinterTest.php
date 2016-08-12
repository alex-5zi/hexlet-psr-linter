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
        
        $result = ['vfs://home/test.php' => $logger->getLog()];
        
        $root = vfsStream::setup('home');
        vfsStream::newFile('test.php')->at($root)
                                      ->setContent('<?php class PsrLinterTest { public function DconsDtruct(){}}');

        vfsStream::newFile('test2.php')->at($root)
                                       ->setContent('<?php class PsrLinterTest { public function __construct(){}}');
          
        vfsStream::newFile('test3.php')->at($root)
                                       ->setContent('<?php class PsrLinterTest { public function getName(){}}');
        
        vfsStream::newFile('test4.php')->at($root)
                                       ->setContent('<?php class PsrLinterTest { public function GetName(){}}');
        
        $paths = array(vfsStream::url('home/test.php'),
                       vfsStream::url('home/test2.php'),
                       vfsStream::url('home/test3.php'),
                       vfsStream::url('home/test4.php')
                       );
        
        $log = psrLint();
        $this->assertEquals(0, count($log));
        
        $log = psrLint(vfsStream::url('home/test.php'));
        $this->assertEquals($result, $log);
        
        $logger2 = new Logger();
        $logger2->warning(
            "Method name is not in camel caps format",
            [
                                        'line' => 1,
                                        'name' => "GetName"
                                    ]
        );
        
        $result = ['vfs://home/test.php' => $logger->getLog(),
                   'vfs://home/test2.php' => [],
                   'vfs://home/test3.php' => [],
                   'vfs://home/test4.php' => $logger2->getLog()
                  ];
        
        $log = psrLint($paths);
        $this->assertEquals($result, $log);
        
        //SideEffect
        vfsStream::newFile('test5.php')->at($root)
                                       ->setContent('<?php
                                                    include "file.php";
                                                    class PsrLinterTest
                                                    { public function GetName(){} }');
        $logger3 = new Logger();
        $logger3->warning(
            "Method name is not in camel caps format",
            [
                                        'line' => 4,
                                        'name' => "GetName"
                                    ]
        );
        $logger3->warning(
            "A file SHOULD declare new symbols (classes, functions, constants, etc.)
                                and cause no other side effects, or it SHOULD execute logic with
                                side effects, but SHOULD NOT do both."
        );
        $result = ['vfs://home/test5.php' => $logger3->getLog()];
        
        $log = psrLint(vfsStream::url('home/test5.php'));
        
        $this->assertEquals($result, $log);
    }
}
