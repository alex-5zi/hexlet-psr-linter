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
        $this->report = Reporter::getReporter();
    }

    public function testLog()
    {
        $this->report->log(LogLevel::ERROR, "message", []);
        $this->report->error("message", []);
        $this->report->warning("message", []);
        ob_start();
        $this->report->printReport();
        $out = ob_get_contents();
        ob_end_clean();
        //print($out);

        $count = substr_count($out, 'message');
        print_r($count);
        $this->assertEquals($count, 3);
        $count = substr_count($out, 'error');
        $this->assertEquals($count, 2);
    }

    public function testPrintReport()
    {
        // вывод в нужном формате
        $this->assertTrue(true);
    }
}
