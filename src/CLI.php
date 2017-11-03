<?php

namespace hexletPsrLinter;

use hexletPsrLinter\Exceptions\CLIException;
use hexletPsrLinter\Linter;
use hexletPsrLinter\Reporter\Reporter;
use League\CLImate\CLImate;

class CLI
{
    private $fix = false;
    private $files = array();
    private $rules = array();
    private $args;
    private $climate;
    private $report;

    public function __construct($name='root')
    {
        $this->climate = new CLImate;
        $this->climate->output->defaultTo('buffer');
        $this->climate->arguments->add([
          'fix' => [
              'prefix'       => 'f',
              'longPrefix'   => 'fix',
              'description'  => 'fix',
              'noValue'      => true,
          ],
          'rules' => [
              'longPrefix'  => 'rules',
              'description' => 'rules',
          ],
          'help' => [
              'prefix'       => 'h',
              'longPrefix'  => 'help',
              'description' => 'Prints a usage statement',
              'noValue'     => true,
          ],
          'path' => [
              'description' => 'path',
          ],
      ]);
        $this->climate->description('hexlet-psr-linter');
    }

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
        } catch (CLIException $e) { //(CLIException $e)
            fwrite(STDERR, $e->getMessage());
            return $e->getCode();
        }//end try

        foreach ($this->files as $path) {
            if (is_file($path)) {
                $code = file_get_contents($path);
                Linter\lint($code, $path);
            }
        }

        $this->report  = Reporter::getReporter()->getReport();

        if (!empty($this->report)) {
            foreach ($this->report as $key => $value) {
                if (!empty($value)) {
                    //  $climate->comment($key);
                    $arrLog = [];
                    $arrLog[] = [$value['level'],
                                      implode(" : ", $value['context']),
                                      $value['message']
                                    ];
                    $this->climate->to('buffer')->columns($arrLog);
                }
            }
        }

        $out = $this->climate->output->get('buffer')->get();

        fwrite(STDOUT, $out);
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
        //  $this->climate->output->addDefault('buffer');
        $this->climate->to('buffer')->usage();
        $str = $this->climate->output->get('buffer')->get();
        return $str;
    }
}