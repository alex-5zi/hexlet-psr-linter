<?php

namespace hexletPsrLinter\Linter;

use hexletPsrLinter\Logger\Logger;

class LinterTest extends \PHPUnit_Framework_TestCase
{

    public function testLint()
    {
        $result = new Logger();
        $result->warning(
            "Method name is not in camel caps format",
            [
                                        'line' => 1,
                                        'name' => "DconsDtruct"
                                    ]
        );

        $log = lint('<?php class PsrLinterTest { public function DconsDtruct(){}}')->getLog();
        $this->assertEquals($result->getLog(), $log);
    }
}
