<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use hexletPsrLinter\Reporter\Reporter;
use function hexletPsrLinter\Linter\isCamelCase;

class ClassNameRule implements RuleInterface
{
    private $reporter;

    private $patch;

    public function __construct()
    {
        $this->reporter = Reporter::getReporter();
    }

    private function addLogClass(Node $node)
    {
        if (!isCamelCase($node->name, true)) {
            $this->reporter->warning(
                    "Class name is not in camelCase format",
                    [
                                        'line' => $this->path.":".$node->getAttribute('startLine'),
                                        'name' => $node->name
                                    ]
                );
        }
        return;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function check(Node $node)
    {
        if ($node instanceof Stmt\Class_) {
            $this->addLogClass($node);
            return;
        }
    }

    public function beforeCheck(array $nodes)
    {
        return;
    }

    public function afterCheck(array $nodes)
    {
        return;
    }

    public function autofix(Node $node)
    {
        return;
    }
}
