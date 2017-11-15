<?php

namespace hexletPsrLinter;

use hexletPsrLinter\Reporter\Reporter;
use PHPUnit\Framework\TestCase;

class LinterUtilsTest extends TestCase
{
    /**
    * @dataProvider lintProvider
    */
    public function testLint($code, $path, $fix, $result, $report)
    {
        $code = Linter\lint($code, $path, $fix);
        $this->assertEquals($report, Reporter::getReporter()->getReport());
        if ($fix) {
            $re = '/'.$result.'/';
            preg_match_all($re, $code, $matches, PREG_SET_ORDER, 0);
            $this->assertEquals(1, count($matches));
        }
    }

    public function lintProvider()
    {
        $code = '<?php'.PHP_EOL;
        $code .= PHP_EOL;
        $code .= 'class myClass'.PHP_EOL;
        $code .= '{'.PHP_EOL;
        $code .= '    protected $name;'.PHP_EOL;
        $code .= '}'.PHP_EOL;
        $report = Reporter::getReporter('test')->getReport();

        $code1 = '<?php'.PHP_EOL;
        $code1 .= 'class myClass'.PHP_EOL;
        $code1 .= '{'.PHP_EOL;
        $code1 .= '    protected $FAILname;'.PHP_EOL;
        $code1 .= '}'.PHP_EOL;
        Reporter::getReporter('test')->warning(
            "Property name is not in camelCase or under_score format",
            [
                            'line' => 'path:4',
                            'name' => 'FAILname'
                        ]
        );
        $report1 = Reporter::getReporter('test')->getReport();

        $code2 = '<?php'.PHP_EOL;
        $code2 .= 'class myClass'.PHP_EOL;
        $code2 .= '{'.PHP_EOL;
        $code2 .= '    protected $fix_name;'.PHP_EOL;
        $code2 .= '}'.PHP_EOL;
        $report2 = Reporter::getReporter('test')->getReport();
        $code2Result = 'fixName';

        $code3 = '<?php'.PHP_EOL;
        $code3 .= 'class myClass'.PHP_EOL;
        $code3 .= '{'.PHP_EOL;
        $code3 .= 'protected $name;'.PHP_EOL;
        $code3 .= 'public function SetFailName($_fail_name)'.PHP_EOL;
        $code3 .= '{'.PHP_EOL;
        $code3 .= '    $this->name = $_fail_name;'.PHP_EOL;
        $code3 .= '}'.PHP_EOL;
        $code3 .= '}'.PHP_EOL;
        Reporter::getReporter('test')->warning(
            "Method name is not in camelCase format",
            [
                            'line' => 'path:5',
                            'name' => 'SetFailName'
                        ]
        );
        Reporter::getReporter('test')->warning(
            "Variable name is not in camelCase or under_score format",
            [
                            'line' => 'path:7',
                            'name' => '_fail_name'
                        ]
        );
        $report3 = Reporter::getReporter('test')->getReport();

        return [
                [$code, 'path', false, $code, $report],
                [$code1, 'path', false, $code1,  $report1],
                [$code2, 'path', true, $code2Result, $report2],
                [$code3, 'path', false, $code3, $report3]
            ];
    }
}
