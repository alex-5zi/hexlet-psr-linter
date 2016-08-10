<?php

namespace hexletPsrLinter\Linter;

use org\bovigo\vfs\vfsStream;

class LinterTest extends \PHPUnit_Framework_TestCase
{

    public function testLint()
    {
        $result =
            [
             [  'line' => 1,
                'column' => 0,
                'level' => "WARNING",
                'message' => "Method name is not in camel caps format",
                'name' => "DconsDtruct"
              ]
            ];
            
        $this->assertEquals($result, lint('<?php class PsrLinterTest { public function DconsDtruct(){}}'));
    }
}
