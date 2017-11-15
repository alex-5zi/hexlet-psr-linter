<?php

namespace hexletPsrLinter\Linter;

use PHPUnit\Framework\TestCase;

class LinterUtilsTest extends TestCase
{
    /**
    * @dataProvider isCamelCaseProvider
    */
    public function testIsCamelCase($item, $upper, $twoCaps, $result)
    {
        $this->assertEquals($result, isCamelCase($item, $upper, $twoCaps));
    }

    public function isCamelCaseProvider()
    {
        return [
                ['isCamelCaps',false, false, true],
                ['IsCamelCaps',false, false, false],
                ['iscamelcaps',false, false, true],
                ['isCamelCAPS',false, false, false],
                ['i',false, false, true],
                ['IsCamelCaps',true, false, true],
                ['isCamelCaps',true, false, false],
                ['Iscamelcaps',true, false, true],
                ['IsCamelCAPS',true, false, false],
                ['IsCamelCAPS',true, true, true],
                ['I',true, false, true],
                ['IsCamelCaps2',true, false, true],
                ['IsCamel2Caps',true, false, true],
                ['2IsCamelCaps',false, false, false],
                ['2IsCamelCaps',true, false, false],
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
