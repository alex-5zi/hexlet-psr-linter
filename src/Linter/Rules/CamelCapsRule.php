<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Logger\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class CamelCapsRule implements RuleInterface
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
        $this->log = new Logger();
    }

    private function addLogFunction(Node $node)
    {
        if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)) {
                $this->log->warning(
                    "Function name is not in camel caps format",
                    [
                                        'line' => $node->getAttribute('startLine'),
                                        'name' => $node->name
                                    ]
                );
        }
        return;
    }
    
    private function addLogClassMethod(Node $node)
    {
        $methodName = $node->name;
        if (preg_match('|^__|', $methodName)) {
            $magicPart = strtolower(substr($methodName, 2));
            if (!isset($this->magicMethods[$magicPart])) {
                $this->log->warning(
                    'Method name is invalid; only PHP magic methods should be prefixed with a double underscore',
                    [
                                    'line' => $node->getAttribute('startLine'),
                                    'name' => $node->name
                                ]
                );
            }
            return;
        }
        if (!\PHP_CodeSniffer::isCamelCaps($methodName, false, true, true)) {
            $this->log->warning(
                "Method name is not in camel caps format",
                [
                                'line' => $node->getAttribute('startLine'),
                                'name' => $node->name
                            ]
            );
        }
            return;
    }
    
    private function addLogClassVariable(Node $node)
    {
        if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)
                && !\PHP_CodeSniffer::isCamelCaps($node->name, true, true, true)) {
            $this->log->warning(
                "Variable name is not in camel caps format",
                [
                                'line' => $node->getAttribute('startLine'),
                                'name' => $node->name
                            ]
            );
        }
            return;
    }
    
    private function addLogClassPropertyProperty(Node $node)
    {
        if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)
                && !\PHP_CodeSniffer::isCamelCaps($node->name, true, true, true)) {
            $this->log->warning(
                "Property name is not in camel caps format",
                [
                                'line' => $node->getAttribute('startLine'),
                                'name' => $node->name
                            ]
            );
        }
            return;
    }
    
    public function check(Node $node)
    {
        if ($node instanceof Stmt\Function_) {
            $this->addLogFunction($node);
            return;
        }
        if ($node instanceof Stmt\ClassMethod) {
            // Is this a magic method. i.e., is prefixed with "__" ?
            $this->addLogClassMethod($node);
            return;
        }
        if ($node instanceof Node\Expr\Variable) {
            $this->addLogClassVariable($node);
            return;
        }
        if ($node instanceof Node\Stmt\PropertyProperty) {
            $this->addLogClassPropertyProperty($node);
            return;
        }
        return;
    }
     
    public function getLog()
    {
        return $this->log;
    }
    
    public function beforeCheck(array $nodes)
    {
        return;
    }
    
    public function afterCheck(array $nodes)
    {
        return;
    }
}
