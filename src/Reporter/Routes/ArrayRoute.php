<?php
namespace hexletPsrLinter\Reporter\Routes;

use League\CLImate\CLImate;
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
        // $climate = new CLImate;
        // $arrLog = [];
        // $arrLog[] = [$level,
        //               implode(" : ", $context),
        //               $message
        //             ];
        // $climate->columns($arrLog);
    }

    public function printReport()
    {
        $climate = new CLImate;
        if (!empty($this->report)) {
            foreach ($this->report as $key => $value) {
                if (!empty($value)) {
                    //  $climate->comment($key);
                    $arrLog = [];
                    $arrLog[] = [$value['level'],
                                  implode(" : ", $value['context']),
                                  $value['message']
                                ];
                    $climate->columns($arrLog);
                }
            }
        }
    }
}
