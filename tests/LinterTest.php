<?php

namespace hexletPsrLinter\Linter;

use org\bovigo\vfs\vfsStream;

class LinterTest extends \PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $lint = new Linter();
        $this->assertEquals(1, count($lint->lint('<?php class PsrLinterTest { public function DconsDtruct(){}}')));
    }
}
