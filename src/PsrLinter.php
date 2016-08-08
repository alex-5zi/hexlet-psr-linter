<?php

namespace hexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeDumper;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;

class PsrLinter
{
    private $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function run() {
        $code = file_get_contents($target);
        $this->lint($code);
    }
    
    public function lint($code)
    {
        $f = new "CodeSniffer\PHP_CodeSniffer_File";
        
        return $this->arg;
    }
}
