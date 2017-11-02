<?php

namespace hexletPsrLinter;

use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testIsCamelCaps()
    {
        $this->assertTrue(isCamelCaps('isCamelCaps'));
        $this->assertFalse(isCamelCaps('IsCamelCaps'));
        $this->assertFalse(isCamelCaps('is_Camel_Caps'));
        $this->assertFalse(isCamelCaps('isCamelCAPS'));
    }
}
