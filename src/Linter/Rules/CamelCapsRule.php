<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Reporter\Reporter;
use Psr\Log\LogLevel;

class CamelCapsRule implements RuleInterface
{
    private $reporter;

    private $patch;

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
        $this->reporter = Reporter::getReporter();
    }

    private function isCamelCaps($string)
    {
        $legalChars = '^[a-z][a-zA-Z0-9]{1,}$';
        if (preg_match("/$legalChars/", $string) === 0) {
            return false;
        }

        $twoCaps = '[A-Z]{2,}';
        if (preg_match("/$twoCaps/", $string) > 0) {
            return false;
        }

        return true;
    }

    private function addLogFunction(Node $node)
    {
        if (!$this->isCamelCaps($node->name)) {
            $this->reporter->warning(
                    "Function name is not in camel caps format",
                    [
                                        'line' => $this->path.":".$node->getAttribute('startLine'),
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
                $this->reporter->warning(
                    'Method name is invalid; only PHP magic methods should be prefixed with a double underscore',
                    [
                                    'line' => $this->path.":".$node->getAttribute('startLine'),
                                    'name' => $node->name
                                ]
                );
            }
            return;
        }
        if (!$this->isCamelCaps($methodName)) {
            $this->reporter->warning(
                "Method name is not in camel caps format",
                [
                                'line' => $this->path.":".$node->getAttribute('startLine'),
                                'name' => $node->name
                            ]
            );
        }
        return;
    }

    private function addLogClassVariable(Node $node)
    {
        if (!$this->isCamelCaps($node->name)) {
            $this->reporter->warning(
                "Variable name is not in camel caps format",
                [
                                'line' => $this->path.":".$node->getAttribute('startLine'),
                                'name' => $node->name
                            ]
            );
        }
        return;
    }

    private function addLogClassPropertyProperty(Node $node)
    {
        if (!$this->isCamelCaps($node->name)) {
            $this->reporter->warning(
                "Property name is not in camel caps format",
                [
                                'line' => $this->path.":".$node->getAttribute('startLine'),
                                'name' => $node->name
                            ]
            );
        }
    }

    public function setPath($path)
    {
        $this->path = $path;
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

    public function beforeCheck(array $nodes)
    {
        return;
    }

    public function afterCheck(array $nodes)
    {
        return;
    }
}
