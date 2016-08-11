<?php

namespace hexletPsrLinter\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class Logger extends AbstractLogger
{
    
    private $logger;
    
    //private $arrayLogLevel =  array(
    //        LogLevel::EMERGENCY => "EMERGENCY",
    //        LogLevel::ALERT => "ALERT",
    //        LogLevel::CRITICAL => "CRITICAL",
    //        LogLevel::ERROR => "ERROR",
    //        LogLevel::WARNING => "WARNING",
    //        LogLevel::NOTICE => "NOTICE",
    //        LogLevel::INFO => "INFO",
    //        LogLevel::DEBUG => "DEBUG"
    //);

    public function __construct()
    {
        $this->logger = [];
    }
    
    public function log($level, $message, array $context = array()) {
        $this->logger[] = [
                'level' => $level,
                'message' => $message,
                'context' => $context
        ];
    }
     
    public function getLog() {
        return $this->logger;
    }
}