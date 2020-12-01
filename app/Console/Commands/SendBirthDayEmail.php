<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\People;
use App\User;
use Carbon\Carbon;
use Mail;


class SendBirthDayEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Auto Email when user's Birthday";

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
        // dd(Carbon::now()->format("m-d"));

        // $users = People::with('user')->whereDob(date('Y-m-d'))->get();
        // 
        $users = People::with('user')->whereMonth('dob', '=', date('m'))->whereDay('dob', '=', date('d'))->get();

        if($users){
            $userAll = User::all();

            foreach($userAll as $user)
            {
               
                Mail::queue('emails.birthdayNotice', ['user' => [$user,$users]], function ($mail) use ($user,$users) {
                    $mail->to($user->email)
                        ->from('nicolecross1579@gmail.com', 'Management Information System | Krishaweb')
                        ->subject("Krishaweb Team");
                });

                $this->info("User Birthday mail sent successfully!");
            }    
        }
        
    }
}
