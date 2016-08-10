<?php

namespace hexletPsrLinter;

use League\CLImate\CLImate;

function printLog($log)
{
    $climate = new CLImate;
    if (!empty($log)) {
        foreach ($log as $key => $value) {
            $climate->comment($key);
            $climate->table($value);
        }
    }
}

function createArrayFile($paths = array())
{
    $resultPaths = array();
    foreach ($paths as $path) {
        if (is_dir($path)) {
            $directory = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($iterator as $info) {
                if ($info->isFile() && $info->getExtension() == 'php') {
                    array_push($resultPaths, $info->getPathname());
                }
            }
        } else {
            if (is_file($path)) {
                array_push($resultPaths, $path);
            }
        }
    }
    
    return $resultPaths;
}
