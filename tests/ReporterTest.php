<?php

namespace hexletPsrLinter;

use hexletPsrLinter\Reporter\Reporter;
use Psr\Log\LogLevel;
use PHPUnit\Framework\TestCase;

class ReporterTest extends TestCase
{
    private $report;

    protected function setUp()
    {
        $this->report = Reporter::getReporter('test');
    }

    public function testLog()
    {
        $this->report->getReport();
        $this->report->log(LogLevel::ERROR, "message", []);
        $this->report->error("message", []);
        $this->report->warning("message", []);
        $arr = $this->report->getReport();
        $count = count($arr);
        $this->assertEquals($count, 3);
    }

    public function testgetReport()
    {
        $this->report->getReport();
        $this->report->log(LogLevel::ERROR, "message", []);
        $this->report->error("message", []);
        $this->report->warning("message", []);
        $arr = $this->report->getReport();
        $this->assertEquals($arr[0]['level'], LogLevel::ERROR);
        $this->assertEquals($arr[1]['message'], 'message');
        $this->assertEquals($arr[2]['message'], 'message');
    }
}
