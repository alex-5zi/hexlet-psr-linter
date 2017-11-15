<?php

namespace hexletPsrLinter;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use hexletPsrLinter\Exceptions\CLIException;
use hexletPsrLinter\CLI;

class CLITest extends TestCase
{
    private $root;

    protected function setUp()
    {
        $structure = array('Core' => array(
                            'Rules' => array(
                                                       'rule1.php'    => 'text content',
                                                       'rule2.php'   => 'text content',
                                                       'file.txt' => 'text content',
                                                ),
                            'AnEmptyFolder'   => array(),
                            'src' => array(
                                                       'file1.php'    => 'text content',
                                                       'file2.php'   => 'text content',
                                                       'file3.php'   => 'text content',
                                                       'file.txt' => 'text content',
                                                )
                            )
                           );

        $root = vfsStream::setup('root', null, $structure);
    }

    public function testScanpath()
    {
        //$this->setExpectedException('CLIException');

        $class = new \ReflectionClass('hexletPsrLinter\CLI');
        $method = $class->getMethod('scanpath');
        $method->setAccessible(true);
        $obj = new CLI();
        //test 1
        $dee = false;
        $path = vfsStream::url('root/Core/src2');

        try {
            $method->invoke($obj, $path);
        } catch (CLIException $e) {
            $this->assertTrue(true);
            $dee = true;
        }
        if (!$dee) {
            $this->fail('Not raise an exception');
        }

        //test 2
        $path = vfsStream::url('root/Core/Rules');
        try {
            $result = $method->invoke($obj, $path);
            $this->assertEquals(count($result), 2);
        } catch (CLIException $e) {
            $this->fail('Not raise an exception');
        }

        //test 3
        $path = vfsStream::url('root/Core');
        try {
            $result = $method->invoke($obj, $path);
            $this->assertEquals(count($result), 5);
        } catch (CLIException $e) {
            $this->fail('Not raise an exception');
        }
    }

    public function testSetCommandLineValues()
    {
        $class = new \ReflectionClass('hexletPsrLinter\CLI');
        $method = $class->getMethod('setCommandLineValues');
        $method->setAccessible(true);
        $args = $class->getProperty('args');
        $args->setAccessible(true);
        try {
            $obj = new CLI();
            $args->setValue($obj, array("-f", "--rules", vfsStream::url('root/Core/Rules'), vfsStream::url('root/Core/src')));
            $method->invoke($obj);
            $this->assertEquals(count($obj->getFiles()), 3);
            $this->assertEquals(count($obj->getRules()), 2);
            $this->assertEquals(count($obj->getFix()), true);
        } catch (CLIException $e) {
            $this->fail('Not raise an exception');
        }

        try {
            $obj = new CLI();
            $args->setValue($obj, array());
            $method->invoke($obj);
            $this->fail('Not raise an exception');
        } catch (CLIException $e) {
            $this->assertTrue(true);
        }

        try {
            $obj = new CLI();
            $args->setValue($obj, array("--fix", "--rules", vfsStream::url('root/Core/Rules')));
            $method->invoke($obj);
            $this->assertEquals(count($obj->getFiles()), 0);
            $this->assertEquals(count($obj->getRules()), 2);
            $this->assertEquals(count($obj->getFix()), true);
        } catch (CLIException $e) {
            $this->fail('Not raise an exception');
        }
    }

    public function testProcessArgument()
    {
        $class = new \ReflectionClass('hexletPsrLinter\CLI');
        $method = $class->getMethod('processArgument');
        $method->setAccessible(true);
        $args = $class->getProperty('args');
        $args->setAccessible(true);
        $obj = new CLI();
        $args->setValue($obj, array("-f", "--rules", vfsStream::url('root/Core/Rules'), vfsStream::url('root/Core/src')));
        try {
            $method->invoke($obj, 'h', 0);
            $this->fail('Not raise an exception');
        } catch (CLIException $e) {
            $this->assertTrue(true);
        }
        try {
            $method->invoke($obj, 'fix', 0);
            $this->assertEquals(count($obj->getFix()), true);
        } catch (CLIException $e) {
            $this->fail('Not raise an exception');
        }
        try {
            $method->invoke($obj, 'rules', 1);
            $this->assertEquals(count($obj->getRules()), 2);
        } catch (CLIException $e) {
            $this->fail('Not raise an exception');
        }
        try {
            $method->invoke($obj, 'sdfgg', 0);
            $this->fail('Not raise an exception');
        } catch (CLIException $e) {
            $this->assertTrue(true);
        }
    }
}
