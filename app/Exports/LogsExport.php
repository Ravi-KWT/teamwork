<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LogsExport implements FromView, ShouldAutoSize
{

    public $logs;
    public function __construct($logs)
    {
    	$this->logs = $logs;
    }

    public function view(): View
    {
        $arr = array();
        foreach($this->logs as $log) {
            if($log['description'])
            {
                $log['description'];
            }
            else
            {
                $log['description'] = "-";
            }
            $t1 = strtotime($log->start_time);
            $t2 = strtotime($log->end_time);
            $differenceInSeconds = $t2 - $t1;
            $differenceInMinutes = floor( ($differenceInSeconds / 60) % 60) ;
            $differenceInHours = number_format( floor( $differenceInSeconds / 3600) ,2);
            $logdate = \Carbon\Carbon::parse($log['date'])->format('d-m-Y');
            $data = array($logdate,$log->user->people->name, $log['task']['project']['name'],$log['task']['project']['company']['name'], $log['description'],$log['task']['category']['name'],$log['task']['name'], $log['start_time'], $log['end_time'],$log->billable?'Yes':'No',$differenceInHours?$differenceInHours:'0',$differenceInMinutes?$differenceInMinutes:'0',$log['hour']);
            array_push($arr, $data);
        }
        return view('exports.logs', [
            'logs' => $arr,
        ]);
    }
}
