<?php

namespace hexletPsrLinter;

use org\bovigo\vfs\vfsStream;

class PsrLinterTest extends \PHPUnit_Framework_TestCase
{

    public function testGetLog()
    {
        $root = vfsStream::setup('home');
        vfsStream::newFile('test.php')->at($root)->setContent('<?php class PsrLinterTest { public function DconsDtruct(){}}');
        $psrlint = new PsrLinter();
        $psrlint->addFile(vfsStream::url('home/test.php'));
        $log = $psrlint->run()->getLog();
        $this->assertEquals(1, count($log));
    }
}
