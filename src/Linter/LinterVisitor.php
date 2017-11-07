<?php
namespace hexletPsrLinter\Linter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class LinterVisitor extends NodeVisitorAbstract
{
    private $rules;
    private $path;
    private $fix;

    public function __construct($rules, $path, $fix)
    {
        $this->rules = $rules;
        $this->path = $path;
        $this->fix = $fix;
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

    public function leaveNode(Node $node)
    {
        if ($this->fix) {
            foreach ($this->rules as $rule) {
                $rule->autofix($node);
            }
        }
    }

    public function afterTraverse(array $nodes)
    {
        foreach ($this->rules as $rule) {
            $rule->afterCheck($nodes);
        }
    }
}
