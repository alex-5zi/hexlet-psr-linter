<?php

namespace hexletPsrLinter\Linter;

use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    /**
    * @dataProvider isCamelCaseProvider
    */
    public function testIsCamelCase($item, $upper, $result)
    {
        $this->assertEquals($result, isCamelCase($item, $upper));
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
        $this->assertEquals($result, isUnderScore($item));
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
