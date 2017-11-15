<?php
namespace hexletPsrLinter\Linter\Rules;

class AutoLoadRules
{
    protected static $rules = array();

    public static function addRules($path)
    {
        $name = basename($path, ".php");
        self::$rules[$name] = $path;
    }


    public static function getArrObjectRules()
    {
        $arrObjectRules = array();
        foreach (self::$rules as $name => $value) {
            $reflector = new \ReflectionClass("hexletPsrLinter\\Linter\\Rules\\".$name);
            $arrObjectRules[] = $reflector->newInstance();
        }
        return $arrObjectRules;
    }

    public static function getRules()
    {
        return self::$rules;
    }

    public static function scanRules()
    {
        $files = scandir(__DIR__);
        $patchs = [];
        foreach ($files as $file) {
            if (substr($file, -8) === 'Rule.php') {
                self::addRules($file);
            }
        }
    }
}
