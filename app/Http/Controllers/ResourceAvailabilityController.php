<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Resource;
use App\Project;
use Illuminate\Support\Facades\Input;
use App\TeamMember;
use Auth;
use Redirect;
use Former\Facades\Former;
use Validator;
use Image;
use Carbon\Carbon;
use App\User;
use Excel;
use App\Events\UserAvailability;
use Event;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\UserToken;
// use OneSignal;
class ResourceAvailabilityController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        
        $teamlead = TeamMember::where('teamlead_id',Auth::user()->id)->first();
        $projects = Project::all();
        if(Auth::user()->is_teamlead == true){
            $workloads1 = Resource::where('date',\Carbon\Carbon::now())->where('teamlead_id',Auth::user()->id)->orderBy('work_load','desc')->get();
            
            $teamMembersIds = $workloads1->pluck('member_id')->toArray();

            $workloads = Resource::where('date',\Carbon\Carbon::now())->where('teamlead_id',Auth::user()->id)->orderBy('work_load','desc')->get();
            
            $resources = TeamMember::where('teamlead_id',Auth::user()->id)->get();

            $todayWorkloads = Resource::where('date',\Carbon\Carbon::now())->whereNotIn('member_id',$teamMembersIds)->get();

            $available_users = User::where('is_available','=',true)->paginate(5);
            return view('resources.index',compact('resources','workloads','todayWorkloads','projects','teamlead','available_users') );

        }

        if(Auth::user()->is_viewer == true && Auth::user()->roles !='admin'){
            $workloads=[];
            $todayWorkloads = Resource::where('date',\Carbon\Carbon::now())->get();

            $available_users = User::where('is_available','=',true)->paginate(5);
            return view('resources.index',compact('todayWorkloads','workloads','available_users','projects'));

        }


        if(Auth::user()->roles == 'admin' ){
            $workloads = Resource::whereDate('created_at', '>=', date('Y-m-d'))->get();
            $resources = TeamMember::where('teamlead_id',Auth::user()->id)->get();
            $users=User::where('id','!=',0)->with('people')->get()->pluck('people.name','id')->toArray();
            asort($users);
            $users=[''=>'All']+$users;  
            $teamleads = User::where('id','!=',0)->where('is_teamlead',true)->with('people')->get()->pluck('people.name','id')->toArray();
            asort($teamleads);
            $teamleads=[''=>'All']+$teamleads;  
            $available_users = User::where('is_available','=',true)->paginate(5);
            return view('resources.summary',compact('resources','workloads','users','teamleads','available_users'));                   
        }



        return redirect()->to('/')->with('info','You do not have rights to access this location');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $members = $request->get('members');

        
        foreach($members as $member_id=>$workload){
            $data = Resource::where('member_id',$member_id)->where('date',date('Y-m-d'))->first();
            if(!$data){
                $data = new Resource;
            }
            $data->member_id = $member_id;
            $data->teamlead_id = $request->get('teamlead_id');
            $data->work_load = $workload['slider_value'];
            $data->on_leave = $workload['on_leave'];
            if (isset($workload['projects'])) {
                $data->projects = implode(",", $workload['projects']);
            }
            else{
                $data->projects = null;   
            }
            $data->others = $workload['others'];
            $data->date = \Carbon\Carbon::now();
            $data->save();
        }
        return back()->with('success','Save Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    public function getResource($id)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
    public function updateWorkload(Request $request){
        $teamlead = TeamMember::where('teamlead_id',$request->get('teamlead_id'))->first();
        if($teamlead){
            $inputData = [];
            $inputData['date']= \Carbon\Carbon::now();
            $inputData['member_id']= $request->get('member_id');
            $inputData['teamlead_id']= $request->get('teamlead_id');
            $inputData['work_load']= $request->get('work_load');
            $matchThese = array('date'=>\Carbon\Carbon::now(),'member_id'=> $request->get('member_id'));
            $re=Resource::updateOrCreate($matchThese,$inputData);
            return Response::json(['success'=>true,'re'=>$re]);    
        }else{
            return Response::json(['success'=>false,'msg'=>'Removed By Admin']);    
        }
        
    }

    public function changeLeaveStatus(Request $request){
            $re = Resource::find($request->id);
            $re->on_leave = $request->leave_status;
            $re->save();
            return Response::json(['success'=>true,'re'=>$re]);    
    }

    public function getfillterResourceAvailability(Request $request){
        $start_date=Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date=Carbon::parse($request->end_date)->format('Y-m-d');
        $users=User::where('id','!=',0)->with('people')->get()->pluck('people.name','id')->toArray();
            asort($users);
            $users=[''=>'All']+$users;  
        $teamleads = User::where('id','!=',0)->where('is_teamlead',true)->with('people')->get()->pluck('people.name','id')->toArray();
            asort($teamleads);
            $teamleads=[''=>'All']+$teamleads;  
        $user_id=$request->user_id;
        $teamlead_id=$request->teamlead_id;

        if($request->filter){
            if($user_id && $start_date && $end_date ){
                 $workloads = Resource::where('member_id',$user_id)->whereBetween('date', [$start_date,$end_date])->orderBy('work_load','desc')->get();
            }else if($teamlead_id && $start_date && $end_date){
                 $workloads = Resource::where('teamlead_id',$teamlead_id)->whereBetween('date', [$start_date,$end_date])->orderBy('work_load','desc')->get();
            }else if($start_date && $end_date){
                $workloads = Resource::whereBetween('date', [$start_date,$end_date])->orderBy('date','desc')->orderBy('date','desc')->get();
            }
            $available_users = User::where('is_available','=',true)->paginate(5);
            return view('resources.summary',compact('workloads','users','start_date','end_date','users','teamleads','available_users'));
        }
        if($request->get('excel'))
        {
            if($user_id && $start_date && $end_date ){
                 $workloads = Resource::where('member_id',$user_id)->whereBetween('date', [$start_date,$end_date])->orderBy('work_load','desc')->get();
           
            }else if($teamlead_id && $start_date && $end_date){
                 $workloads = Resource::where('teamlead_id',$teamlead_id)->whereBetween('date', [$start_date,$end_date])->orderBy('work_load','desc')->get();
            } else if($start_date && $end_date){
                $workloads = Resource::whereBetween('date', [$start_date,$end_date])->orderBy('date','desc')->orderBy('date','desc')->get();
            }
            Excel::create('Reports', function($excel) use ($workloads){
                $excel->sheet('Sheet1', function($sheet) use ($workloads){
                $sheet->row(1, '');
                    $arr =array();
                    foreach($workloads as $workload) {
                        $data =  array($workload['date'],$workload->user->people->name,$workload->teamlead->people->name,$workload->work_load,$workload->on_leave);
                        array_push($arr, $data);
                    }
                    //set the titles
                    $sheet->setFontFamily('arial');
                    $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array('Date','Resource','TeamLead','Workload','On Leave'));
                });
            })->export('xls');    
        }
    }

    //user availability
    public function getUserAvailability(Request $request){

        $user = Auth::user();
        $status = $request->status;
        $user = User::find(Auth::id());
        $user->is_available = $request->status;
        $user->save(); 
        // OneSignal::sendNotificationToUser("Some Message", 'f2d65a3d-f5df-4dc6-98bf-3a62c4b64662 ', $url = null, $data = null);
        // OneSignal::sendNotificationToAll("Some Message");
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(15);
        if ($request->status == "true") {
            $notificationBuilder = new PayloadNotificationBuilder('User Available');
            $notificationBuilder->setBody($user->people->fname .' is now available.')
                        ->setSound('default');
        }
        else{
            $notificationBuilder = new PayloadNotificationBuilder('User Not Available');
            $notificationBuilder->setBody($user->people->fname .' has work so now '. ($user->people->gender == 'male' ? 'he' : 'she').' is not available.')
                        ->setSound('default');   
        }

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // for single notification

        // $token = User::where('notification_token','!=',null)->where('id','!=',Auth::id())->first()->notification_token;

        // $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        // $downstreamResponse->numberSuccess();

        //for multiple user notification

        $tokens = UserToken::whereHas('user',function($query){
            $query->where('roles','=','admin');
            $query->orWhere('is_teamlead','=',true);
        })->pluck('fcm_token')->toArray();
        
        if (count($tokens) > 0) {
            
            $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
            
            $downstreamResponse->tokensToDelete();

            // return Array (key : oldToken, value : new token - you must change the token in your database)
            $downstreamResponse->tokensToModify();
        }
        event(new \App\Events\UserAvailability($user, $status));
        // broadcast(new UserAvailability($user, $status))->toOthers();
        return $status;
    }

    public function getAvailableUsers(Request $request){
      $users = User::where('is_available','=',true)->paginate(5);
      return view('resources.available_users_list',compact('users'));
    }
    
}

