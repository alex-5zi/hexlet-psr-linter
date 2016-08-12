<?php

namespace hexletPsrLinter;

use org\bovigo\vfs\vfsStream;

class PsrLinterUtilsTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateArrayFile()
    {
        
        $structure = array('Core' => array(
                            'AbstractFactory' => array(
                                                       'test.php'    => 'some text content',
                                                       'other.php'   => 'Some more text content',
                                                       'Invalid.csv' => 'Something else',
                                                ),
                            'AnEmptyFolder'   => array(),
                            'badlocation.php' => 'some bad content',
                            ),
                           'Core2' => array(
                            'AbstractFactory' => array(
                                                       'test.php'    => 'some text content',
                                                       'other.php'   => 'Some more text content',
                                                       'Invalid.csv' => 'Something else',
                                                ),
                            'AnEmptyFolder'   => array(),
                            'badlocation.php' => 'some bad content',
                            ),
                           'index.php' => 'some text content'
                           );
        
        vfsStream::setup('home');
        $root = vfsStream::create($structure);
        
        $paths = array(vfsStream::url('home/Core'));
        
        $this->assertEquals(3, count(createArrayFile($paths)));
        
        array_push($paths, vfsStream::url('home/index.php'));
        
        $this->assertEquals(4, count(createArrayFile($paths)));
        
        array_push($paths, vfsStream::url('home/Core2/AbstractFactory'));
        
        $this->assertEquals(6, count(createArrayFile($paths)));
    }
}
