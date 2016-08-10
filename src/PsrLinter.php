<?php

namespace hexletPsrLinter;

//use hexletPsrLinter\Linter\lint;

function psrLint($input = '')
{
    $log = array();
    $files = array();
    if (is_array($input)) {
        $files = $input;
    } else {
        $files[] = $input;
    }
    foreach ($files as $path) {
        if (is_file($path)) {
            $code = file_get_contents($path);
            $logLint = Linter\lint($code);
            if (count($logLint) > 0) {
                $log[$path] = $logLint;
            }
        }
    }
    return $log;
}
