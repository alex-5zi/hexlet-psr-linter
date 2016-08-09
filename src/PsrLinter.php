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
    private $files;
    private $log;

    public function __construct()
    {
        $this->files = [];
        $this->log = [];
    }

    public function addFile($path)
    {
        if (file_exists($path) && is_file($path)) {
            array_push($this->files, $path);   
        }
        return $this;
    }
    
    public function run() {
        foreach ($this->files as $path) {
            $code = file_get_contents($path);
            $log = $this->lint($code);
            if (count($log) > 0) {
                array_push($this->log, ['filename' => $path]);
                array_push($this->log, ['lint' => $log]);
            }
            $this->cliOut();
        }
        return $this;
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
        
        return $nodeVisitor->getLog();
    }
    
    public function cliOut()
    {
        $climate = new CLImate;
        $climate->table($this->log);
    }
}
