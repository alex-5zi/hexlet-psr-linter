<?php
namespace hexletPsrLinter\Linter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class LinterVisitor extends NodeVisitorAbstract
{
    private $rules;
    private $path;

    public function __construct($rules, $path)
    {
        $this->rules = $rules;
        $this->path = $path;
    }

    public function beforeTraverse(array $nodes)
    {
        foreach ($this->rules as $rule) {
            $rule->beforeCheck($nodes);
            $rule->setPath($this->path);
        }
    }

    public function enterNode(Node $node)
    {
        foreach ($this->rules as $rule) {
            $rule->check($node);
        }
    }

    public function afterTraverse(array $nodes)
    {
        foreach ($this->rules as $rule) {
            $rule->afterCheck($nodes);
        }
    }
}
