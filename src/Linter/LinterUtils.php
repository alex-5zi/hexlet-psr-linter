<?php

namespace hexletPsrLinter\Linter;

function isCamelCase($string, $upper = false)
{
    if ($upper) {
        $legalChars = '^[A-Z][a-zA-Z0-9]{0,}$';
    } else {
        $legalChars = '^[a-z][a-zA-Z0-9]{0,}$';
    }

    if (preg_match("/$legalChars/", $string) === 0) {
        return false;
    }

    $twoCaps = '[A-Z]{2,}';
    if (preg_match("/$twoCaps/", $string) > 0) {
        return false;
    }

    return true;
}

function isUnderScore($string)
{
    $legalChars = '^[a-z][a-z0-9_]{0,}$';
    if (preg_match("/$legalChars/", $string) === 0) {
        return false;
    }

    $legalChars = '[a-z0-9]$';
    if (preg_match("/$legalChars/", $string) === 0) {
        return false;
    }

    $twoScore = '[_]{2,}';
    if (preg_match("/$twoScore/", $string) > 0) {
        return false;
    }

    return true;
}
