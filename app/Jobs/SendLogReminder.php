<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Log;
class SendLogReminder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $type;
    protected $users;
    protected $date;
    protected $send_to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->type = $details['type'];
        $this->users = $details['users'];
        $this->date = $details['date'];
        $this->send_to = $details['send_to'];

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('emails.log_reminder', ['type' => $this->type, 'users' => $this->users, 'date' => $this->date], function($message){
        $message->to($this->send_to)->subject('Log Reminder');
        });
    }
}
