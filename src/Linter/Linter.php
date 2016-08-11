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
use hexletPsrLinter\Logger\Logger;

function lint($code, $rules = array())
{
        $allRules = array_merge(
                                [
                                     new CamelCase(),
                                     new SideEffect()
                                ],
                                $rules
                );
        
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser   = new NodeTraverser;
        $nodeVisitor = new LinterVisitor();
        $traverser->addVisitor($rules);
        try {
                $stmts = $parser->parse($code);
                $stmts = $traverser->traverse($stmts);
        } catch (Error $e) {
                $log = new Logger();
                $log->error('Parse Error: '. $e->getMessage());
                return $log;
        }
     
        return $nodeVisitor->getLog();
}
