<?php
namespace hexletPsrLinter\Reporter;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

abstract class Route extends AbstractLogger implements LoggerInterface
{
    // /**
    //  * @var bool Включен ли роут
    //  */
    // private $isEnable = true;
    //
    // public function enable()
    // {
    //     $this->isEnable = true;
    // }
    //
    // public function disable()
    // {
    //     $this->isEnable = false;
    // }
    //
    // public function getIsEnable()
    // {
    //     return $this->isEnable;
    // }
}
