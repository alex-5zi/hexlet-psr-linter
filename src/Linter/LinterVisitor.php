<?php
namespace hexletPsrLinter\Linter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Logger\Logger;

class NodeVisitor extends NodeVisitorAbstract
{
    private $rules;
    
    public function __construct($rules)
    {
        $this->rules = $rules;
    }
    
    public function beforeTraverse(array $nodes){   
        foreach ($this->rules as $rule) {
            $rule->beforeCheck($node);
        }
    }
    
    public function enterNode(Node $node)
    {
        foreach ($this->rules as $rule) {
            $rule->check($node);
        }
    }
    
    public function afterTraverse(array $nodes){
        foreach ($this->rules as $rule) {
            $rule->afterCheck($node);
        }
    }    
    
    public function getLog()
    {
        $log = new Logger();
        foreach ($this->rules as $rule) {
            foreach ($rule->getLog() as $value) {
                $log->log($value['level'], $value['message'], $value['context']);    
            }
        }
        return $log;
    }
}