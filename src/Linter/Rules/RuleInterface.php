<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use hexletPsrLinter\Reporter\Reporter;

interface RuleInterface
{
    public function beforeCheck(array $nodes);
    public function check(Node $node);
    public function afterCheck(array $nodes);
    public function setPath($path);
}
