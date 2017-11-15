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

    public function __construct($name = 'root')
    {
        $this->routes = new SplObjectStorage();
        $this->routes->attach(new ArrayRoute(), 'ArrayRoute');
        $this->name = $name;
    }

    public static function getReporter($name = 'root')
    {
        if (!isset(self::$reporters[$name])) {
            self::$reporters[$name]=new Reporter($name);
        }

        return self::$reporters[$name];
    }

    public function log($level, $message, array $context = [])
    {
        $this->routes->rewind();
        while ($this->routes->valid()) {
            $route = $this->routes->current();
            $route->log($level, $message, $context);
            $this->routes->next();
        }
    }

    public function getReport($nameRoute = 'ArrayRoute')
    {
        $this->routes->rewind();
        while ($this->routes->valid()) {
            $route = $this->routes->current();
            $data   = $this->routes->getInfo();
            if ($data == $nameRoute) {
                return $route->getReport();
            }
            $this->routes->next();
        }
    }
}
