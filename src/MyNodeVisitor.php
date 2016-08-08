<?php

namespace hexletPsrLinter;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;

class MyNodeVisitor extends NodeVisitorAbstract
{
    private $log;

    protected $magicMethods = array(
                               'construct'  => true,
                               'destruct'   => true,
                               'call'       => true,
                               'callstatic' => true,
                               'get'        => true,
                               'set'        => true,
                               'isset'      => true,
                               'unset'      => true,
                               'sleep'      => true,
                               'wakeup'     => true,
                               'tostring'   => true,
                               'set_state'  => true,
                               'clone'      => true,
                               'invoke'     => true,
                               'debuginfo'  => true,
                              );
    
    public function __construct()
    {
        $this->log = [];
    }

    public function validate($node)
    {
        if ($node instanceof Stmt\Function_) {
            if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)) {
                return "Function name is not in camel caps format";
            }
            return true;
        }
        if ($node instanceof Stmt\ClassMethod) {    
            // Is this a magic method. i.e., is prefixed with "__" ?
            $methodName = $node->name;
            if (preg_match('|^__|', $methodName) !== 0) {
                $magicPart = strtolower(substr($methodName, 2));
                if (isset($this->magicMethods[$magicPart]) === false) {
                    return 'Method name is invalid; only PHP magic methods should be prefixed with a double underscore';
                } else
                {
                    return true;
                }
            }
            if (!\PHP_CodeSniffer::isCamelCaps($methodName, false, true, true)) {
                return "Method name is not in camel caps format";
            }
            return true;
        }
        return true;
    }
    
    public function enterNode(Node $node)
    {
        $result = $this->validate($node);
        if ($result !== true) {
            $this->log[] = [
                'line' => $node->getAttribute('startLine'),
                'column' => 0,
                'level' => "WARNING",
                'message' => $result,
                'name' => $node->name
            ];
        }
    }
    
    public function getLog()
    {
        return $this->log;
    }
    
}
