<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{User, LogTime, TeamMember};
use App\Jobs\SendLogReport;
use Carbon\Carbon;
use Mail;
use Log;
use Auth;
use DateTime;
use DB;
class WeekReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "weekly team report send to team lead";

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
        $day = date('D');
        $weekNoOfMonth = Carbon::now()->weekNumberInMonth;
        if ( (in_array($weekNoOfMonth,[1,2,3]) && $day == 'Fri')  || (in_array($weekNoOfMonth, [4,5]) && $day == 'Sat')) 
        {
            $end_date = date('Y-m-d');
            $start_date = date('Y-m-d', strtotime($day == 'Fri' ? '-4 days' : '-5 days', strtotime($end_date)));
            $team_leads_with_members = TeamMember::with(['member'])->get()->groupBy('teamlead_id');
            $team_lead_with_users_reports = [];
            $team_billable_hours = 0;
            $team_non_billable_hours = 0;
            foreach ($team_leads_with_members as $key => $users) {
                $team_billable_hours = 0;
                $team_non_billable_hours = 0;
                $total_team_members = $users->count() - 1;
                if ($users->count() > 0 ) {
                    
                    foreach ($users as $user) {
                        $user_hours = DB::table('log_times')
                        ->where('user_id','=',$user->member_id)
                        ->whereBetween('date',[$start_date,$end_date])
                        ->selectRaw("sum(case when billable = '0' then Hour end) as non_billable_hours")
                        ->selectRaw("sum(case when billable = '1' then Hour end) as billable_hours")
                        ->first();
                        if ($user->teamlead_id == $user->member_id) {
                            $team_lead_with_users_reports[$user->teamlead_id]['email'] = $user->member->email;
                            $team_lead_with_users_reports[$user->teamlead_id]['name'] = $user->member->people->name;
                        }
                        else{

                        $team_non_billable_hours += $user_hours->non_billable_hours;
                        $team_billable_hours += $user_hours->billable_hours;
                        $team_lead_with_users_reports[$user->teamlead_id]['users'][] = ['id' => $user->member_id, 'name' => $user->member->people->name,'billable_hours' => $user_hours->billable_hours,'non_billable_hours' => $user_hours->non_billable_hours];
                        }
                        $team_lead_with_users_reports[$user->teamlead_id]['team_billable_hours'] = $team_billable_hours;
                        $team_lead_with_users_reports[$user->teamlead_id]['team_non_billable_hours'] = $team_non_billable_hours;
                        $team_lead_with_users_reports[$user->teamlead_id]['total_users'] = $total_team_members;
                        $team_lead_with_users_reports[$user->teamlead_id]['total_days'] = $day == 'Fri' ? 4 :5;;
                    }
                }
            }

            foreach ($team_lead_with_users_reports as $key => $team_lead) 
            {
                dispatch(new SendLogReport($team_lead, $start_date, $end_date));   
            }
        }
    }
}
