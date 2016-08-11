<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Logger\Logger;
use Psr\Log\LoggerInterface;


class SideEffectsRule extends RuleInterface
{
    private $log;
    
    private $flagDeclaration;
    private $flagSideEffect;
    
    public function __construct()
    {
        $this->log = new Logger;
    }

    public function check($node){   
        switch ($node) {
            case "Stmt_Function":
            case "Stmt_Class":
                $this->flagDeclaration = true;
                break;
            case "Stmt_Function":
            case "Stmt_Class":
            case "Stmt_Class":
                $this->flagSideEffect = true;
                break;
        }
        return;
    }
    
    public function beforeCheck(array $nodes){   
        $this->flagDeclaration = false;
        $this->flagDeclaration = false;
    }
    
    
    public function afterCheck(array $nodes){
        if ($this->flagSideEffect && $this->flagDeclaration) {
            $this->log->warning('A file SHOULD declare new symbols (classes, functions, constants, etc.)
                                and cause no other side effects, or it SHOULD execute logic with
                                side effects, but SHOULD NOT do both.');
        }
    }
    
    public function getLog()
    {
        return $this->log;
    }
}