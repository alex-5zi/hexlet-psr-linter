<?php

namespace hexletPsrLinter\Linter\Rules;

use PhpParser\Node;
use Psr\Log\LoggerInterface;
use hexletPsrLinter\Logger\Logger;

interface RuleInterface
{
    public function beforeCheck(array $nodes);
    public function check(Node $node);
    public function afterCheck(array $nodes);
    public function getLog();
}
