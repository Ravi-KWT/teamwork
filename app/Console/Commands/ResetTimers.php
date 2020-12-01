<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Timer;
use App\{User, LogTime, Interval};
use Carbon\Carbon;
use Mail;
use Log;
use Auth;
class ResetTimers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:timers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "submit all timers";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timers = Timer::all();
        foreach ($timers as $timer) 
        {
            Log::info($timer->running);
            if ($timer->running == true) 
            {
                $timer->running = false;
                

                $interval = Interval::where('timer_id',$timer->id)->latest()->first();
                $to = date('Y-m-d h:i:s');
                $interval->to = $to;

                $from = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $interval->from);
                $to = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $to);
                $minutes = $to->diffInSeconds($from);
                
                $interval->duration = $minutes;
                $interval->save();

                $seconds = 0;
                $intervals = \App\Interval::where('timer_id',$timer->id)->get();
                foreach ($intervals as $interval) 
                {
                    $seconds += $interval->duration;   
                }
                $timer->duration = $seconds;
                $timer->save();
            } 
            $differenceInSeconds = $timer->duration;
            $differenceInMinutes = round($differenceInSeconds / 60);
            if ($differenceInMinutes == 0) {
                $differenceInMinutes = 1;
                $differenceInSeconds = 60;
            }
            $differenceInHours = $differenceInSeconds / 3600;
            if($differenceInHours<0) {
                $differenceInHours += 24;
            }
            $logtimes=New Logtime;
            $logtimes->hour = number_format($differenceInHours,2);
            if($differenceInMinutes>=0)
            {
                $logtimes->minute = $differenceInMinutes;
            }
            
            $current_time = strtotime(date("Y-m-d H:i:s"));
            $time = $current_time - ($differenceInMinutes * 60);
            $start_time = date("H:i", $time);
            $end_time = date("H:i",$current_time);

            $logtimes->date = date('Y-m-d', strtotime($timer->created_at));
            $logtimes->start_time = $start_time;
            $logtimes->end_time = $end_time;
            $logtimes->user_id = $timer->user_id;
            $logtimes->description = $timer->description;
            $logtimes->task_id = $timer->task_id;
            $logtimes->project_id = $timer->project_id;
            $logtimes->billable = 1;
            $logtimes->save();
            $timer->delete();
        }        
    }
}
