<?php

namespace hexletPsrLinter\Logger;

use Psr\Log\LogLevel;

class LoggerTest extends \PHPUnit_Framework_TestCase
{

    public function testLog()
    {
        $log = new Logger();
        $log->log(
            LogLevel::WARNING,
            "warning",
            [
                        'warning' => ""
                    ]
        );
        $log->warning("warning");
        $log->error("error");
        $log->emergency("emergency");
        $log->alert("alert");
        $log->critical("critical");
        $log->notice("notice");
        $log->info("info");
        $log->debug("debug");
    
        $this->assertEquals(9, count($log->getLog()));
    }
    
    public function testGetLog()
    {
        $result = [[
                'level' => 'warning',
                'message' => 'message',
                'context' => ['context']
        ]];
        
        $log = new Logger();
        $log->warning('message', ['context']);
        $this->assertEquals($result, $log->getLog());
    }
}
