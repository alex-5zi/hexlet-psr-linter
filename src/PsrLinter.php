<?php

namespace hexletPsrLinter;

use hexletPsrLinter\Linter\Linter;
use League\CLImate\CLImate;

class PsrLinter
{
    private $files;
    private $log;

    public function __construct()
    {
        $this->files = [];
        $this->log = [];
    }

    public function addFile($path)
    {
        if (file_exists($path) && is_file($path)) {
            array_push($this->files, $path);
        }
        return $this;
    }
    
    public function run()
    {
     //   $this->log = [];
        foreach ($this->files as $path) {
            $code = file_get_contents($path);
            $lint = new Linter;
            $log = $lint->lint($code);
            if (count($log) > 0) {
                //array_push($this->log, ['filename' => $path]);
                $this->log[$path] = $log;
            }
        }
        return $this;
    }
    
    public function getLog()
    {
        return $this->log;
    }
    
    public function printLog()
    {
        $climate = new CLImate;
        if (!empty($log)) {
            foreach ($log as $key => $value) {
                $climate->comment($key);
                $climate->table($value);
            }
        }
    }
}
