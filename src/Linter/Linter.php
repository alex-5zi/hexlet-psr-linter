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
use hexletPsrLinter\Reporter\Reporter;
use Psr\Log\LogLevel;

function lint($code, $path='', $rules = array())
{
    $allRules = array_merge(
            [
                                     new Rules\FunctionNameRule(),
                                     new Rules\VariableNameRule(),
                                     new Rules\SideEffectsRule()
                                ],
            $rules
        );

    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    $traverser   = new NodeTraverser;
    $nodeVisitor = new LinterVisitor($allRules, $path);
    $traverser->addVisitor($nodeVisitor);
    try {
        $stmts = $parser->parse($code);
        $stmts = $traverser->traverse($stmts);
    } catch (Error $e) {
        Reporter::getReporter()->error('Parse Error: '. $e->getMessage());
    }
}
