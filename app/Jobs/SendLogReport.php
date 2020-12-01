<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Log;
class SendLogReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $details;
    protected $start_date;
    protected $end_date;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details, $start_date, $end_date)
    {
        $this->details = $details;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::send('emails.log_report', ['team_lead' => $this->details,'start_date' => $this->start_date, 'end_date' => $this->end_date], function($message){
        // $message->to($this->details['email'])->subject('Weekly Team Log Report');
            $message->to('niravp@krishaweb.com')->subject('Weekly Team Log Report');
        });
    }
}
