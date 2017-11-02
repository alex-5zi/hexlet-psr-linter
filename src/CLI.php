<?php

namespace hexletPsrLinter;

use hexletPsrLinter\Exceptions\CLIException;
use hexletPsrLinter\Linter;
use hexletPsrLinter\Reporter\Reporter;

class CLI
{
    private $fix = false;
    private $files = array();
    private $rules = array();
    private $args;

    public function getFiles()
    {
        return $this->files;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getFix()
    {
        return $this->fix;
    }

    private function scanpath($path)
    {
        if (is_dir($path)) {
            $files = scandir($path);
            $patchs = [];
            foreach ($files as $file) {
                if (substr($file, -4) === '.php') {
                    $patchs[] = $file;
                }
            }
            return  $patchs;  //glob($path.DIRECTORY_SEPARATOR.'*.php');
        } elseif (is_file($path)) {
            return [$path] ;
        }

        $output  = "ERROR: File or folder \"$path\" not found".PHP_EOL.PHP_EOL;
        $output .= $this->printUsage();
        throw new CLIException($output, 3);
    }

    public function run()
    {
        $this->args = $_SERVER['argv'];
        try {
            $this->setCommandLineValues();
        } catch (CLIException $e) {
            fwrite(STDERR, $e->getMessage());
            return $e->getCode();
        }//end try

        foreach ($this->files as $path) {
            if (is_file($path)) {
                $code = file_get_contents($path);
                Linter\lint($code, $path);
            }
        }

        Reporter::getReporter()->printReport();
    }


    private function setCommandLineValues()
    {
        array_shift($this->args);

        $numArgs = count($this->args);

        if ($numArgs == 0) {
            throw new CLIException($this->printUsage(), 0);
        }
        for ($i = 0; $i < $numArgs; $i++) {
            $arg = $this->args[$i];
            if ($arg === '') {
                continue;
            }
            if ($arg{0} === '-') {
                if ($arg === '-' || $arg === '--') {
                    continue;
                }
                if ($arg{1} === '-') {
                    $i = $this->processArgument(substr($arg, 2), $i);
                } else {
                    $switches = str_split($arg);
                    foreach ($switches as $switch) {
                        if ($switch === '-') {
                            continue;
                        }
                        $i = $this->processArgument($switch, $i);
                    }
                }
            } else {
                $this->processUnknownArgument($arg, $i);
            } //end if
        } //end for
    }

    private function processArgument($arg, $pos)
    {
        switch ($arg) {
            case 'h':
            case '?':
            case 'help':
                $output = $this->printUsage();
                throw new CLIException($output, 0);
            case 'f':
            case 'fix':
                $this->fix = true;
                break;
            case 'rules':
                $pos++;
                if (isset($this->args[($pos)]) === false) {
                    $output = 'ERROR: Setting a rules option requires a value'.PHP_EOL.PHP_EOL;
                    $output .= $this->printUsage();
                    throw new CLIException($output, 3);
                } //end if
                $value   = $this->args[($pos)];
                $this->rules = array_merge($this->rules, $this->scanpath($value));
                break;
            default:
                $output  = "ERROR: option \"$arg\" not known".PHP_EOL.PHP_EOL;
                $output .= $this->printUsage();
                throw new CLIException($output, 3);
        } //end switch
        return $pos;
    }

    private function processUnknownArgument($arg)
    {
        $this->files = array_merge($this->files, $this->scanpath($arg));
    }

    private function printUsage()
    {
        return 'Usage: hexlet-psr-linter [--fix] [--rules <file>] <file> ...' . PHP_EOL;
    }
}
