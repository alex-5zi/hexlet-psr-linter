<?php

namespace hexletPsrLinter;

use org\bovigo\vfs\vfsStream;

class PsrLinterTest extends \PHPUnit_Framework_TestCase
{

    public function testAddFile()
    {
        $root = vfsStream::setup('home');
        vfsStream::newFile('test.php')->at($root)
                                      ->setContent('<?php class PsrLinterTest { public function DconsDtruct(){}}');
        $psrlint = new PsrLinter();
        $this->assertEquals($psrlint, $psrlint->addFile(vfsStream::url('home/test.php')));
    }
    
    public function testGetLog()
    {
        $psrlint = new PsrLinter();
        $log = $psrlint->getLog();
        $this->assertEquals(0, count($log));
    }
    
    public function testRun()
    {
        $root = vfsStream::setup('home');
        vfsStream::newFile('test.php')->at($root)
                                      ->setContent('<?php class PsrLinterTest { public function DconsDtruct(){}}');
        $psrlint = new PsrLinter();
        $psrlint->addFile(vfsStream::url('home/test.php'));
        $log = $psrlint->run()->getLog();
        $this->assertEquals(1, count($log));
        
        vfsStream::newFile('test2.php')->at($root)
                                      ->setContent('<?php class PsrLinterTest { public function __construct(){}}');
        $psrlint = new PsrLinter();
        $psrlint->addFile(vfsStream::url('home/test2.php'));
        $log = $psrlint->run()->getLog();
        $this->assertEquals(0, count($log));
        
        vfsStream::newFile('test3.php')->at($root)
                                      ->setContent('<?php class PsrLinterTest { public function getName(){}}');
        $psrlint = new PsrLinter();
        $psrlint->addFile(vfsStream::url('home/test3.php'));
        $log = $psrlint->run()->getLog();
        $this->assertEquals(0, count($log));
        
        vfsStream::newFile('test4.php')->at($root)
                                      ->setContent('<?php class PsrLinterTest { public function GetName(){}}');
        $psrlint = new PsrLinter();
        $psrlint->addFile(vfsStream::url('home/test4.php'));
        $log = $psrlint->run()->run()->getLog();
        $this->assertEquals(1, count($log));
    }
}
