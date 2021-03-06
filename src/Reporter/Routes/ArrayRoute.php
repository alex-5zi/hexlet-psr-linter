<?php
namespace hexletPsrLinter\Reporter\Routes;

use hexletPsrLinter\Reporter\Route;

class ArrayRoute extends Route
{
    private $report = array();

    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);
    // }

    public function log($level, $message, array $context = [])
    {
        $this->report[] = ['level' => $level, 'message' => $message, 'context' => $context];
    }

    public function getReport()
    {
        $report = $this->report;
        $this->report = [];
        return $report;
    }

    // public function printReport()
    // {
    //     $climate = new CLImate;
    //     if (!empty($this->report)) {
    //         foreach ($this->report as $key => $value) {
    //             if (!empty($value)) {
    //                 //  $climate->comment($key);
    //                 $arrLog = [];
    //                 $arrLog[] = [$value['level'],
    //                               implode(" : ", $value['context']),
    //                               $value['message']
    //                             ];
    //                 $climate->columns($arrLog);
    //             }
    //         }
    //     }
    // }
}
