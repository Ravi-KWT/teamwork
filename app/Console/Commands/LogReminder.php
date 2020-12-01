<?php

namespace App\Console\Commands;

use App\Jobs\SendLogReminder;
use App\LogTime;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class LogReminder extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'work:reminder';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = "Reminder to admin your team member not submit today's logs";

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $date = date('2020-08-24');
    $flag = 0;
    if (date('D') != 'Sun') {
      $flag = 1;
      if (date('D') == 'Sat') {
        $weekNoOfMonth = Carbon::now()->weekNumberInMonth;

        if ($weekNoOfMonth == 4 || $weekNoOfMonth == 5) {
          $flag = 1;
        } else {
          $flag = 0;
        }
      }
    }

    if ($flag == 1) {
      $reminder_users_list_for_teamlead = [];
      $reminder_users = [];
      $logs = LogTime::where('date', '=', $date)->pluck('user_id')->toArray();
      $logged_users = array_unique(array_values($logs));
      $project_manager = [23, 30, 3, 29];
      $logged_users = array_merge($logged_users, $project_manager);

      $users = User::with(['people.department.teamHead', 'workLoads'])->where('active', '=', true)->whereNotIn('id', $logged_users)->get();
      if ($users) {
        foreach ($users as $user) {
          if ($user->people->department && $user->people->department->teamHead) {
            $reminder_users_list_for_teamlead[$user->people->department->teamHead->teamlead_id][] = $user->people->name;
            array_push($reminder_users, $user->people->name);
          }
          $details['type'] = 1;
          $details['users'] = $user;
          $details['date'] = $date;
          $details['send_to'] = $user->email;
          dispatch(new SendLogReminder($details));
        }
      }
      if ($reminder_users_list_for_teamlead) {
        foreach ($reminder_users_list_for_teamlead as $key => $user) {
          $team_lead_email = User::where('id', '=', $key)->first();
          $details['type'] = 2;
          $details['users'] = $user;
          $details['date'] = $date;
          $details['send_to'] = $team_lead_email->email;
          dispatch(new SendLogReminder($details));
        }
      }
      if (!empty($reminder_users)) {
        $details['type'] = 2;
        $details['users'] = $reminder_users;
        $details['date'] = $date;
        // $details['send_to'] = ['parth@krishaweb.com','gunjan@krishaweb.com','ashmi@krishaweb.com'];
        dispatch(new SendLogReminder($details));
      }
      Log::info('log reminder cron works.');
    }
  }

  // public function getWeeks($date) {
  //     // estract date parts
  //     list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));
  //     $w = 1;
  //     // for each day since the start of the month
  //     for ($i = 1; $i <= $d; ++$i) {
  //         // if that day was a sunday and is not the first day of month
  //         if ($i > 1 && date('w', strtotime("$y-$m-$i")) == 0) {
  //             ++$w;
  //         }
  //     }
  //     return $w;
  // }
}
