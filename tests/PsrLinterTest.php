<?php

namespace hexletPsrLinter;

use org\bovigo\vfs\vfsStream;

class PsrLinterTest extends \PHPUnit_Framework_TestCase
{
    
    public function testPsrLint()
    {
        
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
        $this->assertEquals(1, count($log));
        
        $log = psrLint($paths);
        $this->assertEquals(2, count($log));
    }
}
