<?php

namespace hexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeDumper;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\Error;
use League\CLImate\CLImate;

class PsrLinter
{
    private $file;
    private $log;

    public function __construct()
    {
        $this->log = [];
    }

    public function setFile($path)
    {
        $this->file = $path;
        return $this;
    }
    
    public function run() {
        $code = file_get_contents($this->file);
        $this->lint($code);
        $this->cliOut();
    }
    
    public function lint($code)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser   = new NodeTraverser;    
        $nodeVisitor = new MyNodeVisitor();
        $traverser->addVisitor($nodeVisitor);
        try {
            $stmts = $parser->parse($code);
            $stmts = $traverser->traverse($stmts);
        } catch (Error $e) {
            echo 'Parse Error: '. $e->getMessage();
        }
    
        //$f = CodeSniffer\PHP_CodeSniffer_File::;
        
        $this->log = $nodeVisitor->getLog();
    }
    
    public function cliOut()
    {
        $climate = new CLImate;
        $climate->table($this->log);
    }
}
