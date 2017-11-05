<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Reporter\Reporter;
use function hexletPsrLinter\Linter\isCamelCase;

class FunctionNameRule implements RuleInterface
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


    private function addLogFunction(Node $node)
    {
        if (!isCamelCase($node->name)) {
            $this->reporter->warning(
                    "Function name is not in camelCase format",
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
        if (!isCamelCase($methodName)) {
            $this->reporter->warning(
                "Method name is not in camelCase format",
                [
                                'line' => $this->path.":".$node->getAttribute('startLine'),
                                'name' => $node->name
                            ]
            );
        }
        return;
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
