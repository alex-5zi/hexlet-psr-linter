<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Reporter\Reporter;
use function hexletPsrLinter\Linter\isCamelCase;
use function hexletPsrLinter\Linter\isUnderScore;

class VariableNameRule implements RuleInterface
{
    private $reporter;
    private $patch;

    protected $phpReservedVars = array(
                               '_SERVER'              => true,
                               '_GET'                 => true,
                               '_POST'                => true,
                               '_REQUEST'             => true,
                               '_SESSION'             => true,
                               '_ENV'                 => true,
                               '_COOKIE'              => true,
                               '_FILES'               => true,
                               'GLOBALS'              => true,
                               'http_response_header' => true,
                               'HTTP_RAW_POST_DATA'   => true,
                               'php_errormsg'         => true,
                             );


    public function __construct()
    {
        $this->reporter = Reporter::getReporter();
    }

    private function addLogClassVariable(Node $node)
    {
        if (isset($this->phpReservedVars[$node->name])) {
            return;
        }
        if (!(isCamelCase($node->name) || isUnderScore($node->name))) {
            $this->reporter->warning(
                "Variable name is not in camelCase or under_score format",
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
        if (!(isCamelCase($node->name) || isUnderScore($node->name))) {
            $this->reporter->warning(
                "Property name is not in camelCase or under_score format",
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

    public function autofix(Node $node)
    {
        if (($node instanceof Node\Expr\Variable) || ($node instanceof Node\Stmt\PropertyProperty)) {
            if (isUnderScore($node->name)) {
                $arr = explode('_', $node->name);
                foreach ($arr as $key => $value) {
                    if ($key > 0) {
                        $arr[$key] = ucwords($value);
                    }
                }
                $name = implode($arr);
                $node->name = $name;
            }
        }
    }
}
