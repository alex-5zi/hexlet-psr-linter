<?php

namespace hexletPsrLinter\Linter;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Logger\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


class CamelCapsRule extends RuleInterface
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

    public function check($node)
    {
        if ($node instanceof Stmt\Function_) {
            if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)) {
                $this->log->warning("Function name is not in camel caps format",
                                    [
                                        'line' => $node->getAttribute('startLine'),
                                        'name' => $node->name
                                    ]
                                   );
            }
            return;
        }
        if ($node instanceof Stmt\ClassMethod) {
            // Is this a magic method. i.e., is prefixed with "__" ?
            $methodName = $node->name;
            if (preg_match('|^__|', $methodName)) {
                $magicPart = strtolower(substr($methodName, 2));
                if (!isset($this->magicMethods[$magicPart])) {
                    $this->log->warning('Method name is invalid; only PHP magic methods should be prefixed with a double underscore',
                                    [
                                        'line' => $node->getAttribute('startLine'),
                                        'name' => $node->name
                                    ]
                                   );
                } 
                return;
            }
            if (!\PHP_CodeSniffer::isCamelCaps($methodName, false, true, true)) {
                $this->log->warning("Method name is not in camel caps format",
                                    [
                                        'line' => $node->getAttribute('startLine'),
                                        'name' => $node->name
                                    ]
                                   );
            }
            return;
        }
        if ($node instanceof Node\Expr\Variable) {
            if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)
                && !\PHP_CodeSniffer::isCamelCaps($node->name, true, true, true))
            {
                $this->log->warning("Variable name is not in camel caps format",
                                    [
                                        'line' => $node->getAttribute('startLine'),
                                        'name' => $node->name
                                    ]
                                   );
            }
            return;
        }
        if ($node instanceof Node\Stmt\PropertyProperty) {
            if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)
                && !\PHP_CodeSniffer::isCamelCaps($node->name, true, true, true))
            {
                $this->log->warning("Property name is not in camel caps format",
                                    [
                                        'line' => $node->getAttribute('startLine'),
                                        'name' => $node->name
                                    ]
                                   );
            }
            return;
        }
        return;
    }
     
    public function getLog()
    {
        return $this->log;
    }
}
