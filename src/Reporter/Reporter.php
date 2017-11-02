<?php
namespace hexletPsrLinter\Reporter;

use SplObjectStorage;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use hexletPsrLinter\Reporter\Routes\ArrayRoute;

class Reporter extends AbstractLogger implements LoggerInterface
{
    protected static $reporters=array();
    protected $name;
    protected $routes;

    public function __construct($name='root')
    {
        $this->routes = new SplObjectStorage();
        $this->routes->attach(new ArrayRoute());
        $this->name = $name;
    }

    public static function getReporter($name='root')
    {
        if (!isset(self::$reporters[$name])) {
            self::$reporters[$name]=new Reporter($name);
        }

        return self::$reporters[$name];
    }

    public function log($level, $message, array $context = [])
    {
        foreach ($this->routes as $route) {
            if (!$route instanceof Route) {
                continue;
            }
            if (!$route->getIsEnable()) {
                continue;
            }
            $route->log($level, $message, $context);
        }
    }

    public function printReport()
    {
        foreach ($this->routes as $route) {
            if (!$route instanceof Route) {
                continue;
            }
            if (!$route->getIsEnable()) {
                continue;
            }
            $route->printReport();
        }
    }
}
