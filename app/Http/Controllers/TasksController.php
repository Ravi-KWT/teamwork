<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Task;
use App\LogTime;
use App\User;
use App\Project;
use App\ProjectCategory;
use App\TaskCategory;
use App\TaskUser;
use App\People;
use App\Department;
use App\Timer;
use App\Interval;
use Redirect;
use Auth;
use App\Exports\LogsExport;
use Excel;
use DB;
use Carbon\Carbon;
use Mail;
use PDF;
use Dompdf\Options ;
use App\Company;
use Former;
use Validator;
use View;
use Log;
class TasksController extends Controller
{
//////////////////////////////////////////////////////////////
//                    TASK SECTION                          // 
//////////////////////////////////////////////////////////////    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $projects=Project::all();
        $taskCategories=TaskCategory::all();
        $users=People::with('user')->get();
        $tasks = Task::with('timers')->where('project_id','=',$id)->get();
        return view('tasks/index',compact('tasks','projects','taskCategories','id','users'));
    }
    public function getTasks(Request $request)
    {
        $project_id = $request->get('project_id');
        $tasks = '';
        if(Auth::user()->roles == 'admin')
        {
            $tasks =  Task::with('timers','category','users.people')->where('project_id',$project_id)->get();
        }
        else
        {
            $tasks =  Task::with('timers','category','users.people')->where('project_id',$project_id)->whereHas('users', function($q){
                $q->where('user_id','0');
            })->orWhereHas('users', function($q){
                $q->where('user_id',Auth::user()->id);
            })->where('completed',false)->get();  
        }
        foreach($tasks as &$tc)
        {
            $tc = $tc->category;
        }
        $taskcategories = TaskCategory::where('project_id',$project_id)->orderBy('name')->get();
        //get project's users        
        $project=Project::find($project_id);
        // login user detail
        $loginUser = Auth::user();
        $projectUsers= $project->users()->with('people')->orderBy('email')->get();
        
        return response()->json(array('tasks'=>$tasks,'users'=>$projectUsers, 'taskcategories'=>$taskcategories,'loginUser'=>$loginUser->people));
    }
    public function getTaskName($id,Request $request)
    {
        $task = Task::where('id',$id)->first();
        return response()->json($task);
    }
   
    public function postTaskStatus(Request $request)
    {
        
        $task = Task::find($request->get('id'));
        $task->completed = $request->get('completed') == 'true' ? true : false;
        $task->save();
        return response()->json(['success'=>true,'completed'=>$task->completed]);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tasks=Task::create($request->all());
        $tasks->users()->sync($request->get('user_id') ? $request->get('user_id') : [0]);
        $tasks->completed = false;
        
        if(Auth::user()->people->lname)
            $tasks->assignedby = Auth::user()->people->fname.' '.Auth::user()->people->lname ;
        else
            $tasks->assignedby = Auth::user()->people->fname;
        $tasks->save();
        if($request->get('user_id'))
        {
            $taskAssignedUsers = User::whereIn('id',$request->get('user_id'))->where('id','<>',Auth::user()->id)->pluck('email')->toArray();
            Mail::send('emails.taskAssign', ['task_info'=>$tasks->toArray()], function($message)use ($tasks,$taskAssignedUsers) {
                $message->to($taskAssignedUsers);
                $message->subject('New Task Assigned');
            });
        }
        return response()->json(['success'=>true]);
    }
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        return view('tasks.view');
    }
    public function getTaskDetail($project_id,$task_id,Request $request)
    {
        if ($task_id != 'Undefined') 
        {
            $task =Task::with('users.people','logtimes','project','timers')->where('id', '=',$task_id)->first();
            $project = Project::find($project_id);
            $projectUsers = $task->users()->with('people')->orderBy('email')->get();
            
            //$task = Task::where('project_id','=',$project->id)->with('users.people','logtimes')->get();
            if(Auth::user()->roles == 'admin' || Auth::user()->is_projectlead == true || Auth::user()->is_teamlead == true)
            {
                $logs = LogTime::with('task','user.people')->whereTaskId($task_id)->orderBy('date','desc')->orderBy('start_time','asc')->get(); 
            }
            else
            {
                $logs = LogTime::with('task','user.people')->where('user_id', '=',Auth::id())->whereTaskId($task_id)->orderBy('date','desc')->orderBy('start_time','asc')->get();
            }   
            $billable = $logs->where('billable',true)->sum('minute');
            $non_billable = $logs->where('billable',false)->sum('minute');
            $total_task_billable_hours = number_format($billable/60,2);
            $total_task_non_billable_hours = number_format($non_billable/60,2);
            $total_task_minute = $logs->sum('minute');
            $total_task_hours = number_format($total_task_minute/60,2);
            return response()->json(array('task'=>$task,'users'=>$projectUsers,'logs'=>$logs,'billable'=>$billable,'total_task_minute'=>$total_task_minute,'total_task_hours'=>$total_task_hours,'total_task_billable_hours'=>$total_task_billable_hours,'total_task_non_billable_hours'=>$total_task_non_billable_hours));
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function getTask($id)
    {
        $task = Task::findOrFail($id);
        $task_users=array();
        foreach ($task->users as  $u) {
            if($u->id != 0)
            {
                array_push($task_users, $u->people);
            }
            
        }
        $project=Project::find($task->project_id);
        $projectUsers= $project->users()->with('people')->orderBy('email')->get();
        return response()->json(array('task'=>$task,'task_users'=>$task_users,'allUsers'=>$projectUsers));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($request->get('id'));
        $task->users()->sync($request->get('user_id') ? $request->get('user_id') : []);
        $task->update($request->all());
        return response()->json(['success'=>true]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id,$task_id)
    {
        
        $timer = Timer::where('task_id','=',$task_id)->delete();
        $task = Task::find($task_id);
        $task->delete();
        return response()->json(['success'=>true]);
    }
//////////////////////////////////////////////////////////////
//                    Log Timer SECTION               // 
//////////////////////////////////////////////////////////////
    public function startLogTimer(Request $request)
    {
        $current_timer = Timer::where([['running','=',1],['user_id','=',Auth::user()->id]])->first();
        if ($current_timer) 
        {
            $current_timer->running = 0;
            $current_timer->save();
            $interval = Interval::where('timer_id',$current_timer->id)->latest()->first();
            $from = $interval->from;
            $to = date('Y-m-d h:i:s');
            $from = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $interval->from);
            $end = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $to);
            $duration = $end->diffInSeconds($from);
            // $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
            $interval->duration = $duration;
            $interval->to = $to;
            $interval->save();

            $seconds = 0;
            $intervals = \App\Interval::where('timer_id',$current_timer->id)->get();
            foreach ($intervals as $interval) 
            {
                $seconds += $interval->duration;   
            }
            $current_timer->duration = $seconds;
            $current_timer->save();

        }
        if ($request->has('timer_id')) 
        {
            $timer = Timer::find($request->timer_id);
            $timer->running = true;
            $timer->last_started_at = date('Y-m-d h:i:s');
            $timer->save();
            $interval = new Interval;
            $interval->timer_id = $request->timer_id;
            $interval->duration = 0;
            $interval->from = date('Y-m-d h:i:s');
            $interval->to = date('Y-m-d h:i:s');
            $interval->save();
            return response($timer,202);
        }
        // $exist_timer = Timer::where([['task_id','=',$request->task_id],['user_id','=',Auth::user()->id]])->first();
        
        
        // if (!$exist_timer) {
            
            $timer = new Timer;
            $timer->user_id = Auth::user()->id;
            $timer->project_id = $request->project_id;
            $timer->task_id = $request->task_id;
            $timer->running = 1;
            $timer->last_started_at = date('Y-m-d h:i:s');
            $timer->duration = 0;
            $timer->save();
            

            $interval = new Interval;
            $interval->timer_id = $timer->id;
            $interval->duration = 0;
            $interval->from = date('Y-m-d h:i:s');
            $interval->to = date('Y-m-d h:i:s');
            $interval->save();

            $view = View::make('shared.render_log_timers');
            $html = $view->render();
            return response()->json(['render_log_timers'=>$html,'timer'=>$timer]);
        // }
        // else
        // {
        //     $exist_timer->last_started_at = date('Y-m-d h:i:s');
        //     $exist_timer->running = true;
        //     $seconds = 0;
        //     $intervals = \App\Interval::where('timer_id',$exist_timer->id)->get();
        //     foreach ($intervals as $interval) 
        //     {
        //         $seconds += $interval->duration;   
        //     }
        //     $exist_timer->duration = $seconds;
        //     $exist_timer->save();
        //     $interval = new Interval;
        //     $interval->timer_id = $exist_timer->id;
        //     $interval->duration = 0;
        //     $interval->from = date('Y-m-d h:i:s');
        //     $interval->to = date('Y-m-d h:i:s');
        //     $interval->save();
        //     return response($exist_timer,202);
        // }
    }

    public function pauseLogTimer(Request $request)
    {
        // if ($request->has('timer_id')) {
            $timer = Timer::find($request->timer_id);
            $timer->running = false;
            

            $interval = Interval::where('timer_id',$request->timer_id)->latest()->first();
            $to = date('Y-m-d h:i:s');
            $interval->to = $to;

            $from = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $interval->from);
            $to = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $to);
            $minutes = $to->diffInSeconds($from);
            
            $interval->duration = $minutes;
            $interval->save();

            $seconds = 0;
            $intervals = \App\Interval::where('timer_id',$request->timer_id)->get();
            foreach ($intervals as $interval) 
            {
                $seconds += $interval->duration;   
            }
            $timer->duration = $seconds;
            $timer->save();
            $duration = gmdate("H:i:s", $seconds);
            return response()->json(['timer'=>$timer,'duration'=>$duration],202);
        // }
    }
    // delete log timer
    public function deleteLogTimer(Request $request)
    {
        if ($request->timer_id) {
            $timer = Timer::find($request->timer_id);
            $timer->delete();
        }
        return view('shared.render_log_timers');
    }
    //submit log 
    public function submitLogTimer(Request $request)
    {
        $timer = Timer::find($request->timer_id);

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
        $logtimes->user_id = Auth::user()->id;
        $logtimes->description = $request->get('description');
        $logtimes->task_id = $timer->task_id;
        $logtimes->project_id = $timer->project_id;
        $logtimes->billable = $request->get('billable') == "true" ? 1 : 0;
        $logtimes->save();
        $timer->delete();

        return view('shared.render_log_timers');
    }
//////////////////////////////////////////////////////////////
//                    LOGTIMES SECTION                      // 
//////////////////////////////////////////////////////////////
    //this functin is used for save log 
    public function logStore(Request $request)
    {
        $rules = array(
            'billable' => 'required',
        );

        $messages=array(
          'billable.required' => 'Select atleast one.'
      );
        $validator = Validator::make($request->all(),$rules,$messages);
                //if validation fails then it will redirect back with errors
        if ($validator->fails()) { 
            return response()->json(['success'=>false,'errors'=>$validator->errors()]);
            
        }
        
        $start_time =  $request->get('start_time');
        $end_time =  $request->get('end_time');
        $t1  = strtotime($start_time);
        $t2 = strtotime($end_time);
        $differenceInSeconds = $t2 - $t1;
        $differenceInMinutes = $differenceInSeconds / 60;
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
        $logtimes->start_time= $start_time;
        $logtimes->end_time= $end_time;
        $logtimes->date=$request->get('date');
        $logtimes->user_id=Auth::user()->id;
        $logtimes->description=$request->get('description');
        $logtimes->task_id=$request->get('task_id');
        $logtimes->project_id=$request->get('project_id');
        $logtimes->billable=$request->get('billable') == "Billable" ? true : false;
        $logtimes->save();
        return response()->json(['success'=>true,'log'=>$logtimes]);
    }
    //This function is used for change billable/non billable status
    public function postLogBillable(Request $request)
    {
        $logtime = Logtime::find($request->get('id'));
        $logtime->billable = $request->get('billable');
        $logtime->save();
        return response()->json(['success'=>true]);
    }
    //This function is used for get All Logtimes
    public function getLogtimes(Request $request)
    {
        if(Auth::user()->roles=='admin')
        {
            $logs = LogTime::with('task','user.people')->whereTaskId($request->get('task_id'))->orderBy('date','desc')->orderBy('start_time','asc')->get(); 
        }
        else
         {
            $logs = LogTime::with('task','user.people')->where('user_id',Auth::user()->id)->whereTaskId($request->get('task_id'))->orderBy('date','desc')->orderBy('start_time','asc')->get();
         }   
    
        return response()->json($logs);
    }
    //This function is used for Edit Logtime
    public function getLogtime($id)
    {
        $logtime = LogTime::findOrFail($id);
        $user=User::with('people')->whereId($logtime->user_id)->first();
        
        return response()->json(['logtime'=>$logtime,'username'=>$user]);
    }
    //This function is used for Update Logtime
    public function logUpdate(Request $request, $id)
    {
          $rules = array(
              'billable' => 'required',
          );

          $messages=array(
            'billable.required' => 'Select atleast one.'
        );
          $validator = Validator::make($request->all(),$rules,$messages);
                  //if validation fails then it will redirect back with errors
          if ($validator->fails()) { 
              return response()->json(['success'=>false,'errors'=>$validator->errors()]);
              
          }

        $logtimes = Logtime::find($request->get('id'));
        $start_time =  $request->get('start_time');
        $end_time =  $request->get('end_time');
        $t1  = strtotime($start_time);
        $t2 = strtotime($end_time);
        $differenceInSeconds = $t2 - $t1;
        $differenceInMinutes = $differenceInSeconds / 60;
        $differenceInHours = $differenceInSeconds / 3600;
        if($differenceInHours<0) {
            $differenceInHours += 24;
        }
        // $logtimes->user_id=$request->get('user_id');
        // $logtimes->task_id=$request->get('task_id');
        $logtimes->hour = number_format($differenceInHours, 2) ;
        if($differenceInMinutes<=0)
        {
            $logtimes->minute = 0;
        }
        else
        {
            $logtimes->minute = $differenceInMinutes;
        }
        $logtimes->start_time=$request->get('start_time');
        $logtimes->end_time=$request->get('end_time');
        $logtimes->billable=$request->get('billable') == "Billable"? true : false;
        $logtimes->date=$request->get('date');
        $logtimes->description=$request->get('description');
        $logtimes->task_id=$request->get('task_id');
        $logtimes->update();
        return response()->json(['success'=>true]);
    }
    //This function is used for Delete Logtime
    public function logDestroy($id)
    {
        $logtime = Logtime::find($id);
        $logtime->delete();
        return response()->json(['success'=>true]);
    }
//////////////////////////////////////////////////////////////
//                    EVERYTHING PAGE SECTION               // 
//////////////////////////////////////////////////////////////
    public function getEverything()
    {
         $l = 0;
        if(Auth::user()->roles =='admin')
        {
            if(Auth::user()->is_teamlead == false){
                $clientIds = Project::distinct()->pluck('client_id')->toArray();
                $users=User::where('id','!=',0)->with('people')->get()->pluck('people.name','id')->toArray();
                $projectsList=Project::where('status','active')->orderBy('name','asc')->pluck('name','id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList = Department::all()->pluck('name','id')->toArray();
                asort($departmentList);
                $departmentList=[''=>'All']+$departmentList;

            }
            else{

                $users=User::where('id','!=',0)->with('people','team_member')->whereHas('team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->get()->pluck('people.name','id')->toArray();
                $projectsList=Project::where('status','active')->whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->orderBy('name','asc')->pluck('name','id')->toArray();
                $clientIds = Project::whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->distinct()->pluck('client_id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList =[];
            }

            asort($projectsList);
            $projectsList=[''=>'All']+$projectsList;
            
            $project_category_lists = ProjectCategory::all()->pluck('name','id')->toArray();
            asort($project_category_lists);
            $project_category_lists=[''=>'All']+$project_category_lists;

            asort($companyList);
            $companyList=[''=>'All']+$companyList;
            asort($users);
            $users=[''=>'All']+$users;            
            $date = new \Carbon\Carbon;
            $date->subDays(2);
            $logs = LogTime::with('task','user','project')->where('date', '>=', $date->toDateTimeString())->orderBy('date','desc')->orderBy('start_time','asc')->groupBy('id','date')->get();
            $logDates=[];
            foreach($logs as $value)
            {
                array_push($logDates, $value->date);
            }
            arsort($logDates);
            $logDates=array_unique($logDates);
        }

        else
        {
            if(Auth::user()->is_teamlead == true && Auth::user()->is_projectlead == true)
            {    
                $users=User::where('id','!=',0)->with('people','team_member')->whereHas('team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->get()->pluck('people.name','id')->toArray();
                $projects = Project::with(['users','users.people'])->where('projectlead_id','=',Auth::user()->id)->get();
                                
                foreach ($projects as $project)
                {
                    foreach ($project->users as $user) {
                        
                        if (array_key_exists($user->id,$users) == false)
                        {
                            $users[$user->id] = $user->people->name;
                        }
                    }
                }

                asort($users);

                $projectsList=Project::where('status','active')->whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->orderBy('name','asc')->pluck('name','id')->toArray();
                $projectsList1=Project::where('projectlead_id','=',Auth::user()->id)->where('status','active')->pluck('name','id')->toArray();
                foreach ($projectsList1 as $key => $value)
                {
                    if (array_key_exists($key,$projectsList) == false)
                    {
                        array_add($projectsList,$key,$value);
                    }
                }
                $clientIds = Project::whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->distinct()->pluck('client_id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList =[];
                

                // dd($users);
            }
            else if(Auth::user()->is_teamlead == true){

                $users=User::where('id','!=',0)->with('people','team_member')->whereHas('team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->get()->pluck('people.name','id')->toArray();
                $team_lead_projects = Project::where('status','active')->whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->orderBy('name','asc')->pluck('name','id')->toArray();
                $projectsList = Auth::user()->projects->where('status','active')->pluck('name','id')->toArray();
                    foreach ($team_lead_projects as $key => $value)
                    {
                        if (array_key_exists($key,$projectsList) == false)
                        {
                            array_add($projectsList,$key,$value);
                        }
                    }
                $clientIds = Project::whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->distinct()->pluck('client_id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList =[];
                asort($users);
                // $users=[''=>'All']+$users; 
            
            }
            else{

                if(Auth::user()->is_projectlead == true){
                    $proejct_lead_projects=Project::where('projectlead_id','=',Auth::user()->id)->where('status','active')->pluck('name','id')->toArray();
                    $projectsList = Auth::user()->projects->where('status','active')->pluck('name','id')->toArray();
                    foreach ($proejct_lead_projects as $key => $value)
                    {
                        if (array_key_exists($key,$projectsList) == false)
                        {
                            array_add($projectsList,$key,$value);
                        }
                    }
                    
                    $projects = Project::with(['users','users.people'])->where('projectlead_id','=',Auth::user()->id)->get();
                    $users = [];
                    foreach ($projects as $project)
                    {
                        foreach ($project->users as $user) {
                            
                            if (array_key_exists($user->id,$users) == false)
                            {
                                $users[$user->id] = $user->people->name;
                            }
                        }
                    }
                    asort($users);
                    // $users=[''=>'All']+$users; 
                }
                else{
                    
                    $projectsList = Auth::user()->projects->where('status','active')->pluck('name','id')->toArray();
                }
            }
            $date = new \Carbon\Carbon;
            $date->subDays(2);
            asort($projectsList);
            $project_category_lists = ProjectCategory::all()->pluck('name','id')->toArray();
            asort($project_category_lists);
            $project_category_lists=[''=>'All']+$project_category_lists;
            if (isset($users)) 
            {
                $logs = LogTime::with('task','user','project')->whereIN('user_id',array_keys($users))->whereIN('project_id',array_keys($projectsList))->where('date', '>=', $date->toDateTimeString())->orderBy('date','desc')->orderBy('start_time','asc')->groupBy('id','date')->get();
                $users=[''=>'All']+$users;
            }
            else
            {
                $logs = LogTime::with('task','user','project')->where('user_id',Auth::user()->id)->whereIN('project_id',array_keys($projectsList))->where('date', '>=', $date->toDateTimeString())->orderBy('date','desc')->orderBy('start_time','asc')->groupBy('id','date')->get();
            }
            $logDates=[];
            $projectsList=[''=>'All']+$projectsList;
            foreach($logs as $value)
            {
                array_push($logDates, $value->date);
            }
            $logDates=array_unique($logDates);
        }
        $logAllUserList=[];
        foreach($logs as $value)
        {
            $logAllUserList[$value->user->people->id] =$value->user->people->fname.($value->user->people->lname?" ".$value->user->people->lname:''); 
            // array_push($logAllUserList, $value->user->people->fname.($value->user->people->lname?" ".$value->user->people->lname:''));
        }
        
        asort($logAllUserList);
        $logAllUserList=array_unique($logAllUserList);
        // 
        foreach($logs as &$v){
            $v= $v->task->project;
        }
        foreach($logs as &$vc){
         $vc= $vc->task->category;
        }
        foreach ($logs as $key => &$value) {
            $value=$value->user->people->fname. $value->user->people->lname;
        }
        $totalBillableHours = $logs->where('billable',true)->sum('minute'); 
        $totalNonBillableHours = $logs->where('billable',false)->sum('minute');
        $totalLoggedHours = $logs->sum('minute'); 
        $dateWiseLoggedHours = 0;
        $dateWiseBillableHours = 0;
        $dateWiseNonBillableHours = 0;
        if (!isset($users)) {
            $users = [];
        }
        if (!isset($companyList)) {
            $companyList = [];
        }
        if (!isset($departmentList)) {
            $departmentList = [];
        }
        return view('tasks.everything',compact('logs','projectsList','users','logAllUserList','logDates','dateWiseLoggedHours','dateWiseBillableHours','dateWiseNonBillableHours','totalBillableHours','totalNonBillableHours','totalLoggedHours','companyList','l','departmentList','project_category_lists'));

    }

    //new search functionality
    public function postSearch(Request $request){

        $start_date = $request->get('start_date')? \Carbon\Carbon::parse($request->get('start_date'))->format('Y-m-d'):'';
        $end_date = $request->get('end_date')?\Carbon\Carbon::parse($request->get('end_date'))->format('Y-m-d'):'';
        
        $user_id = $request->user_id;
        $project_id = $request->project_id;
        $billable = $request->billable;
        $client_id = $request->client_id;
        $department_id = $request->department_id;
        $project_category_id = $request->project_category_id;
        $query = Logtime::query();

        if(Auth::user()->roles =='admin')
        {
            if(Auth::user()->is_teamlead == false){
                $query->when(request('user_id', false), function ($q) use ($user_id) { 
                    return $q->where('user_id', $user_id);
                });
                $query->when(request('project_category_id', false), function ($q) use ($project_category_id) { 
                    return $q->whereHas('project', function($q) use ($project_category_id) {
                            return $q->where('category_id', '=', $project_category_id);
                        });
                });
                $query->when(request('project_id', false), function ($q) use ($project_id) { 
                    return $q->whereHas('project', function($q) use ($project_id) {
                            return $q->where('id', '=', $project_id);
                        });
                });
                $query->when(request('client_id', false), function ($q) use ($client_id) { 
                    return $q->whereHas('project.company', function($q) use ($client_id) {
                            return $q->where('id', '=', $client_id);
                        });
                });
                $query->when(request('department_id', false), function ($q) use ($department_id) { 
                    return $q->whereHas('user.team_member', function($q) use ($department_id) {
                            return $q->where('department_id', '=', $department_id);
                        });
                });
                $query->when(request('billable',false), function($q) use ($billable){
                    return $q->where('billable', $billable);
                });
                $query->when(request('start_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $query->when(request('end_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $clientIds = Project::distinct()->pluck('client_id')->toArray();
                $users=User::where('id','!=',0)->with('people')->get()->pluck('people.name','id')->toArray();
                $projectsList=Project::where('status','active')->orderBy('name','asc')->pluck('name','id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList = Department::all()->pluck('name','id')->toArray();
                asort($departmentList);
                $departmentList=[''=>'All']+$departmentList;

            }
            else{

                $users=User::where('id','!=',0)->with('people','team_member')->whereHas('team_member',function($q){$q->where('teamlead_id','=',Auth::id());})->get()->pluck('people.name','id')->toArray();
                $projectsList = Project::where('status','active')->whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::id());})->orderBy('name','asc')->pluck('name','id')->toArray();
                $clientIds = Project::whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->distinct()->pluck('client_id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList =[];
                $query->when(request('user_id', false), function ($q) use ($user_id) { 
                    return $q->where('user_id', $user_id);
                });
                if ($request->has('project_id')) {
                    $query->whereHas('project', function($q) use ($project_id) {
                            return $q->where('id', '=', $project_id);
                        });
                    
                }
                else{
                    $query->whereHas('project.users.team_member', function($q) use ($project_id) {
                        return $q->where('teamlead_id', '=', Auth::id());
                    });
                }
                
                $query->when(request('client_id', false), function ($q) use ($client_id) { 
                    return $q->whereHas('project.company', function($q) use ($client_id) {
                        return $q->where('id', '=', $client_id);
                    });
                });
                
                $query->when(request('billable',false), function($q) use ($billable){
                    return $q->where('billable', $billable);
                });
                $query->when(request('start_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $query->when(request('end_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
            }

            
            asort($projectsList);
            $projectsList = [''=>'All']+$projectsList;
            asort($departmentList);
            $departmentList = [''=>'All']+$departmentList;
            $project_category_lists = ProjectCategory::all()->pluck('name','id')->toArray();
            asort($project_category_lists);
            $project_category_lists = [''=>'All']+$project_category_lists;
            asort($companyList);
            $companyList = [''=>'All']+$companyList;
            asort($users);
            $users=[''=>'All']+$users;            
            
        }
        else
        {
            $date = new \Carbon\Carbon;
            $date->subDays(2);
            if (Auth::user()->is_teamlead == true && Auth::user()->is_projectlead == true) {
                // $users=User::where('id','!=',0)->with('people')->get()->pluck('people.name','id')->toArray();
                $project_users = Project::with(['users'])->where('projectlead_id','=',Auth::user()->id)->get();
                $users = array();
                foreach ($project_users as $user) {
                    foreach ($user->users as $key => $value) 
                    {                  
                        $user=User::where('id','=',$value->id)->with('people','team_member')->get();
                        $users[$value->id] = $user[0]->people->name;
                    }
                }
                
                $team_members = \App\TeamMember::with(['member','member.people'])->where('teamlead_id','=',Auth::user()->id)->get();
                foreach ($team_members as $value)
                {
                    
                    if (array_key_exists($value->member->people->id,$users) == false)
                    {
                        $users[$value->member->people->id] = $value->member->people->name;
                        // array_add($users,$value->id,$value->member->people->name);
                    }
                }
                // dd($users);
                asort($users);
                $projectsList=Project::where('status','active')->whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->orderBy('name','asc')->pluck('name','id')->toArray();
                 $projectsList1=Project::where('projectlead_id','=',Auth::user()->id)->where('status','active')->pluck('name','id')->toArray();
                foreach ($projectsList1 as $key => $value)
                {
                    if (array_key_exists($key,$projectsList) == false)
                    {
                        array_add($projectsList,$key,$value);
                    }
                }
                $clientIds = Project::whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->distinct()->pluck('client_id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList =[];
                
                if ($request->has('project_id')) {
                    
                    $query->when(request('project_id', false), function ($q) use ($project_id) { 
                        return $q->whereHas('project', function($q) use ($project_id) {
                                return $q->where('id', '=', $project_id);
                            });
                    });
                }
                else
                {
                    $query->whereHas('project', function($q) use ($projectsList) {
                        return $q->whereIN('id', array_keys($projectsList));
                    });
                }
                // dd($query->toSql(),$query->getBindings(),$query->get(),$request->user_id);
                
                if ($request->has('user_id')) {
                    $query->when(request('user_id', false), function ($q) use ($user_id) { 
                        return $q->where('user_id', $user_id);
                    });
                }
                else
                {
                    $query->whereIN('user_id',array_keys($users));
                }
                if($request->has('user_id') == false && $request->has('project_id') == false){
                    $query->whereIn('user_id',array_keys($users));
                }
                $query->when(request('client_id', false), function ($q) use ($client_id) { 
                    return $q->whereHas('project.company', function($q) use ($client_id) {
                            return $q->where('id', '=', $client_id);
                        });
                });
                
                $query->when(request('billable',false), function($q) use ($billable){
                    return $q->where('billable', $billable);
                });
                $query->when(request('start_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $query->when(request('end_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });

                $users=[''=>'All']+$users; 

                // project lead
                
                
                
                // $query->where('user_id', Auth::user()->id);
                
                // $projectsList=Project::where('projectlead_id','=',Auth::user()->id)->where('status','active')->pluck('name','id')->toArray();

            }
            else if(Auth::user()->is_teamlead == true){

                $users=User::where('id','!=',0)->with('people','team_member')->whereHas('team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->get()->pluck('people.name','id')->toArray();
                // $projectsList=Project::where('status','active')->whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->orderBy('name','asc')->pluck('name','id')->toArray();

                $team_lead_projects = Project::where('status','active')->whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->orderBy('name','asc')->pluck('name','id')->toArray();
                $projectsList = Auth::user()->projects->where('status','active')->pluck('name','id')->toArray();
                    foreach ($team_lead_projects as $key => $value)
                    {
                        if (array_key_exists($key,$projectsList) == false)
                        {
                            array_add($projectsList,$key,$value);
                        }
                    }

                $clientIds = Project::whereHas('users.team_member',function($q){$q->where('teamlead_id','=',Auth::user()->id);})->distinct()->pluck('client_id')->toArray();
                $companyList = Company::whereIn('id',$clientIds)->pluck('name','id')->toArray();
                $departmentList =[];
                if ($request->has('user_id') == true) {
                    $query->when(request('user_id', false), function ($q) use ($user_id) { 
                        return $q->where('user_id', $user_id);
                    });
                }
                else
                {
                    $query->whereIn('user_id', array_keys($users));
                }
                asort($users);
                $users=[''=>'All']+$users;
                if ($request->has('project_id')) {
                    $project_users_array = [];
                    $project = Project::where('id',$project_id)->with('users')->get();
                    
                    foreach ($project[0]->users as $value) {
                            
                            if ($value->team_member) {
                                
                                if ($value->team_member->teamlead_id == Auth::id() && $value->team_member->member_id != Auth::id()) {
                                    array_push($project_users_array, $value->id);
                                }
                            }
                        
                    }
                    $query->where('project_id',$project_id)->whereIn('user_id',$project_users_array); 
                }
                else{
                    $query->whereHas('project.users.team_member', function($q) use ($project_id) {
                            return $q->where('teamlead_id', '=', Auth::user()->id);
                        });
                }
                $query->when(request('client_id', false), function ($q) use ($client_id) { 
                    return $q->whereHas('project.company', function($q) use ($client_id) {
                            return $q->where('id', '=', $client_id);
                        });
                });
                
                $query->when(request('billable',false), function($q) use ($billable){
                    return $q->where('billable', $billable);
                });
                $query->when(request('start_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $query->when(request('end_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
            }
            else if(Auth::user()->is_projectlead == true)
            {
                $project_users = Project::with(['users'])->where('projectlead_id','=',Auth::user()->id)->get();
                $users = [];
                foreach ($project_users as $key => $user) {
                    foreach ($user->users as $key => $value) {
                        
                        array_push($users, $value->id);
                    }
                }
                
                $users=User::whereIN('id',$users)->with('people')->get()->pluck('people.name','id')->toArray();
                asort($users);
                
                $projectsList = Auth::user()->projects->where('status','active')->pluck('id')->toArray();
                // dd($users);
                if ($request->has('project_id')) {
                    $query->when(request('project_id', false), function ($q) use ($project_id) { 
                        return $q->whereHas('project', function($q) use ($project_id) {
                                return $q->where('id', '=', $project_id);
                            });
                    });
                }
                else{
                    $query->whereHas('project', function($q) use ($projectsList) {
                        return $q->whereIN('id', $projectsList);
                    });
                }
                
                // $query->where('user_id', Auth::user()->id);
                if ($request->has('user_id')) 
                {
                    $query->when(request('user_id',false), function($q) use ($user_id){
                        return $q->where('user_id', $user_id);
                    });
                }
                else
                {
                    $query->whereIn('user_id', array_keys($users));
                }
                $users=[''=>'All']+$users; 
                $query->when(request('billable',false), function($q) use ($billable){
                    return $q->where('billable', $billable);
                });
                $query->when(request('start_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $query->when(request('end_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $proejct_lead_projects=Project::where('projectlead_id','=',Auth::user()->id)->where('status','active')->pluck('name','id')->toArray();
                $projectsList = Auth::user()->projects->where('status','active')->pluck('name','id')->toArray();
                foreach ($proejct_lead_projects as $key => $value)
                {
                    if (array_key_exists($key,$projectsList) == false)
                    {
                        array_add($projectsList,$key,$value);
                    }
                }
                // $projectsList=Project::where('projectlead_id','=',Auth::user()->id)->where('status','active')->pluck('name','id')->toArray();
            }
             
            else{
                if ($request->has('project_id')) 
                {
                    $query->when(request('project_id', false), function ($q) use ($project_id) { 
                        return $q->whereHas('project', function($q) use ($project_id) {
                                return $q->where('id', '=', $project_id);
                            });
                    });
                    $query->where('user_id', Auth::user()->id);
                    $query->when(request('billable',false), function($q) use ($billable){
                        return $q->where('billable', $billable);
                    });
                    $query->when(request('start_date',false), function($q) use ($start_date, $end_date){
                        return $q->whereBetween('date', [$start_date,$end_date]);
                    });
                    $query->when(request('end_date',false), function($q) use ($start_date, $end_date){
                        return $q->whereBetween('date', [$start_date,$end_date]);
                    });
                }
                else
                {

                    $query->whereHas('project.users.team_member', function($q) use ($project_id) {
                        return $q->where('member_id', '=', Auth::user()->id);
                    });
                }
                $query->where('user_id', Auth::user()->id);
                $query->when(request('billable',false), function($q) use ($billable){
                    return $q->where('billable', $billable);
                });
                $query->when(request('start_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $query->when(request('end_date',false), function($q) use ($start_date, $end_date){
                    return $q->whereBetween('date', [$start_date,$end_date]);
                });
                $projectsList=Auth::user()->projects->where('status','active')->pluck('name','id')->toArray();
            }
            // dd($projectsList);
            asort($projectsList);
            $projectsList=[''=>'All']+$projectsList;

            $project_category_lists = ProjectCategory::all()->pluck('name','id')->toArray();
            asort($project_category_lists);
            $project_category_lists=[''=>'All']+$project_category_lists;
            
        }
         
        $logs = $query->with('user.people','task.project','task.category')->get();
        // dd($logs);

        $dateWiseLoggedHours = 0;
        $dateWiseBillableHours = 0;
        $dateWiseNonBillableHours = 0;
        $logDates=[];
        foreach($logs as $value)
        {
            array_push($logDates, $value->date);
        }
        arsort($logDates);
        $logDates=array_unique($logDates);
        
        $logAllUserList=array();
        foreach($logs as $value)
        {
            $logAllUserList[$value->user->people->id] = $value->user->people->fname.($value->user->people->lname?" ".$value->user->people->lname:'');
            // array_push($logAllUserList, [$value->user->people->id => $value->user->people->fname.($value->user->people->lname?" ".$value->user->people->lname:'') ]);
        }

        asort($logAllUserList);
        $logAllUserList=array_unique($logAllUserList);
        $projectIds = [];
        foreach($logs as $value)
        {
            array_push($projectIds, $value->project_id);
        }
        $projectIds=array_unique($projectIds);
        $loggedProjectList = "";
        if($client_id){
            $loggedProjectList = Project::distinct()->where('client_id',$client_id)->whereIn('id',$projectIds)->orderBy('name')->pluck('name','id')->toArray();    
        }
        $totalBillableHours = $logs->where('billable',true)->sum('minute'); 
        $totalNonBillableHours = $logs->where('billable',false)->sum('minute');
        $totalLoggedHours = $logs->sum('minute'); 
        if($request->has('excel'))
        {
            return Excel::download(new LogsExport($logs), 'Reports.xlsx');  
        }
        if($request->get('pdf') && $logs->count()>0)
        {
            if(Auth::user()->roles == 'admin'){
                // $font = Font_Metrics::get_font("helvetica", "bold");
                $pdf = \PDF::loadView('reports.log_report',compact('logs','totalBillableHours','totalNonBillableHours','totalLoggedHours','start_date','end_date','project_id'));
                $pdf->setOptions(['isPhpEnabled' => true, 'setIsHtml5ParserEnabled' => true]);
                // $pdf->setIsHtml5ParserEnabled(true);
                $pdf->setPaper('a4','landscape');
                return $pdf->stream('log_report.pdf');
            }
        }
        if($request->get('pdfAttachedToMail') && $logs->count()>0 && $user_id)
        {
            if(Auth::user()->roles=='admin'){
                $pdf = \PDF::loadView('reports.log_report',compact('logs','totalBillableHours','totalNonBillableHours','totalLoggedHours','start_date','end_date','project_id'));
                $pdf->setOptions(['isPhpEnabled'=> true]);
                $pdf->setPaper('a4','landscape');
                $output=$pdf->output();
                file_put_contents(public_path().'/log.pdf',$output);
                $userData=User::find($user_id);
                $reportData = [$start_date,$end_date];
                Mail::send('emails.logreport', ['user'=>$userData,'reportData'=>$reportData], function($message)use ($userData,$reportData) {
                    $message->to($userData->email);
                    $message->subject("Log Report from : ".$reportData[0]." to ".$reportData[1]);
                    $message->attach(public_path().'/log.pdf');
                });


            }
        }

        if($request->get('pdfProjectsReport')  && $logs->count()>0)
        {

                   
            if(Auth::user()->roles == 'admin'){
                if(empty($client_id)){
                    return redirect()->back()->with('error','please select  company to generate report')->withInput();
                }
                else if($client_id && $project_id){
                    return redirect()->back()->with('error','please select only company to generate report')->withInput();
                }
                else{
                $pdf = \PDF::loadView('reports.projects_log_report',compact('logs','totalBillableHours','totalNonBillableHours','totalLoggedHours','start_date','end_date','client_id','loggedProjectList'));
                $pdf->setOptions(['isPhpEnabled'=> true]);
                $pdf->setPaper('a4','landscape');
                return $pdf->stream('projects_log_report.pdf');    
                }
                
            }
        }
        $l = 0;
        if(count($logs)>0)
        {
            $l=1;
        }
        if (!isset($users)) $users = [];
        if (!isset($companyList)) $companyList = [];
        if (!isset($departmentList)) $departmentList = [];

        return view('tasks.everything',compact('logs','users','projectsList','companyList','logAllUserList','logDates','dateWiseLoggedHours','dateWiseBillableHours','dateWiseNonBillableHours','start_date','end_date','project_id','user_id','billable','totalBillableHours','totalNonBillableHours','totalLoggedHours','l','project_category_lists','departmentList'))->withSuccess('Report send successfully');

        // return view('tasks.everything',compact('searched_array'));
        
    }
//////////////////////////////////////////////////////////////
//          CATEGORYWISE DESPLAY TASK  SECTION              //
 //////////////////////////////////////////////////////////////
    public function categoryWiseTasks($id){
        $project=Project::find($id);
        $tasks=Task::where('project_id',$id)->first();
        return view('task_categories.view',compact('project','tasks'));
    }
}
