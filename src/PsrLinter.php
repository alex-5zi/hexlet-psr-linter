<?php

namespace hexletPsrLinter;

use hexletPsrLinter\Linter\Linter;

class PsrLinter
{
    private $files;
    //private $log;

    public function __construct()
    {
        $this->files = [];
        //$this->log = [];
    }
    
    private function addFile($path)
    {
        if (file_exists($path) && is_file($path)) {
            array_push($this->files, $path);
        }
        return;
    }

    public function setFiles($paths = '')
    {
        $this->files = [];
        if (is_array($paths)) {
            foreach ($paths as $path) {
                $this->addFile($path);
            }
        } else {
            $this->addFile($paths);
        }
        return $this;
    }
    
    private function run()
    {
        $log = [];
        foreach ($this->files as $path) {
            if (file_exists($path)) {
                $code = file_get_contents($path);
                $lint = new Linter;
                $logLint = $lint->lint($code);
                if (count($logLint) > 0) {
                    $log[$path] = $logLint;
                }
            }
        }
        return $log;
    }
    
    public function getLog()
    {
        return $this->run();
    }
}
