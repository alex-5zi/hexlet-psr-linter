<?php

namespace hexletPsrLinter\Linter;

use PhpParser\Node;
use PhpParser\NodeDumper;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\Error;
use hexletPsrLinter\Linter\LinterVisitor;

class Linter
{

    public function lint($code)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser   = new NodeTraverser;    
        $nodeVisitor = new LinterVisitor();
        $traverser->addVisitor($nodeVisitor);
        try {
            $stmts = $parser->parse($code);
            $stmts = $traverser->traverse($stmts);
        } catch (Error $e) {
            echo 'Parse Error: '. $e->getMessage();
        }
     
        return $nodeVisitor->getLog();
}

}