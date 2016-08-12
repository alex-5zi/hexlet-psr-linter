<?php

namespace hexletPsrLinter;

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
            $log[$path] = $logLint->getLog();
        }
    }
    return $log;
}
