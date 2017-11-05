<?php

namespace hexletPsrLinter;

use PHPUnit\Framework\TestCase;
use hexletPsrLinter\Linter\Rules\CamelCaseRule;

class UtilsTest extends TestCase
{
    /**
    * @dataProvider isCamelCaseProvider
    */
    public function testIsCamelCase($item, $upper, $result)
    {
        $class = new \ReflectionClass('hexletPsrLinter\Linter\Rules\CamelCaseRule');
        $method = $class->getMethod('isCamelCase');
        $method->setAccessible(true);
        $obj = new CamelCaseRule();

        $this->assertEquals($result, $method->invoke($obj, $item, $upper));
    }

    public function isCamelCaseProvider()
    {
        return [
                ['isCamelCaps',false, true],
                ['IsCamelCaps',false, false],
                ['iscamelcaps',false, true],
                ['isCamelCAPS',false, false],
                ['i',false, true],
                ['IsCamelCaps',true, true],
                ['isCamelCaps',true, false],
                ['Iscamelcaps',true, true],
                ['IsCamelCAPS',true, false],
                ['I',true, true],
                ['IsCamelCaps2',true, true],
                ['IsCamel2Caps',true, true],
                ['2IsCamelCaps',false, false],
                ['2IsCamelCaps',true, false],
            ];
    }

    /**
    * @dataProvider isUnderScoreProvider
    */
    public function testIsUnderScore($item, $result)
    {
        $class = new \ReflectionClass('hexletPsrLinter\Linter\Rules\CamelCaseRule');
        $method = $class->getMethod('isUnderScore');
        $method->setAccessible(true);
        $obj = new CamelCaseRule();

        $this->assertEquals($result, $method->invoke($obj, $item));
    }

    public function isUnderScoreProvider()
    {
        return [
                ['is_under_score', true],
                ['isUnderScore', false],
                ['isunder_score', true],
                ['is_Under_Score', false],
                ['i', true],
                ['isunderscore', true],
                ['isunderscore2', true],
                ['isunder_score_', false],
                ['isunder__score', false],
                ['_is_under_score', false],
                ['1is_under_score', false],
                ['is__under__score', false]
            ];
    }
}
