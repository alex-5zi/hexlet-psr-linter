<?php
namespace hexletPsrLinter\Reporter\Routes;

use hexletPsrLinter\Reporter\Routes\ArrayRoute;
use PHPUnit\Framework\TestCase;

class ArrayRouteTest extends TestCase
{
    private $route;

    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);
    // }

    public function setUp()
    {
        $this->route = new ArrayRoute();
    }


    public function testLog()
    {
        $this->route->log('error', 'message', []);
        $result[] = ['level' => 'error', 'message' => 'message', 'context' => []];
        $report = $this->route->getReport();
        $this->assertEquals($report, $result);
    }

    public function testGetReport()
    {
        $this->route->log('error', 'message', []);
        $result[] = ['level' => 'error', 'message' => 'message', 'context' => []];
        $report = $this->route->getReport();
        $this->assertEquals($report, $result);
    }
}
