<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\People;
use App\Project;
use App\ProjectUser;
use App\Department;
use App\Designation;
use App\User;
use Illuminate\Support\Facades\Input;
use Redirect;
use Former\Facades\Former;
use Validator;
use Image;
use Hash;
use App\UserEducation;
use App\UserExperience;
use Mail;
use App\Country;
use Auth;
use Session;

class PeoplesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $departments = Department::all();
        $designations = Designation::all();
        return view('peoples.index',compact('departments','designations'));
    }

    public function getPeoples(Request $request)
    {

       $peoples = People::with('user','department')->get();
       $countries=Country::all();
       $departments=Department::all();
       $designations=Designation::all();
       return response()->json(['peoples'=>$peoples,'countries'=>$countries,'departments'=>$departments,'designations'=>$designations]);
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
        $data=$request->get('user_detail');
        $rules = array(
            'email' => 'unique:users'
        );

        $messages=array(
          'email.unique' => 'Email already exists'
        );
        $validator = Validator::make($request->get('user_detail'), $rules,$messages);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['error'=>true,'messages'=>$messages]);
        }
        else
            {

                // $pass=str_random(8);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $pass = substr(str_shuffle($permitted_chars), 0, 10);
                $user = new User;
                $user->email = $data['email'];
                $user->password = Hash::make($pass);
                $user->roles = isset($data['roles']) ? $data['roles'] : 'employee';
                $user->is_teamlead = $request->get('is_teamlead');
                $user->is_projectlead = $request->is_projectlead;
                $user->active = true;
                $user->save();
                $user_profile = new People;
                $user_profile->user_id = $user->id;
                $user_profile->fname = $data['fname'] ? $data['fname'] : NULL;
                $user_profile->lname = isset($data['lname']) ? $data['lname'] : NULL;
                $user_profile->mobile = isset($data['mobile']) ? $data['mobile'] : NULL;
                $user_profile->phone =isset($data['phone']) ? $data['phone'] : NULL;
                $user_profile->dob = isset($data['dob']) ? $data['dob'] : NULL;
                $user_profile->photo = isset($data['photo']) ? $data['photo'] : NULL;
                $user_profile->marital_status = isset($data['marital_status']) ? $data['marital_status'] : 'Single';
                $user_profile->gender = isset($data['gender']) ? $data['gender'] : NULL;
                $user_profile->adrs1 = isset($data['adrs1']) ? $data['adrs1'] : NULL;
                $user_profile->adrs2 = isset($data['adrs2']) ? $data['adrs2'] : NULL;
                $user_profile->city = isset($data['city']) ? $data['city'] : NULL;
                $user_profile->state = isset($data['state']) ? $data['state'] : NULL;
                $user_profile->zipcode = isset($data['zipcode']) ? $data['zipcode'] : NULL;
                $user_profile->country = isset($data['country']) ? $data['country'] : NULL;
                $user_profile->pan_number = isset($data['pan_number']) ? $data['pan_number'] : NULL;
                $user_profile->department_id = isset($data['department_id']) ? $data['department_id'] : '0';
                $user_profile->designation_id = isset($data['designation_id']) ? $data['designation_id'] : '0';
                $user_profile->management_level = isset($data['management_level']) ? $data['management_level'] : '0';
                $user_profile->join_date = isset($data['join_date']) ? $data['join_date'] : NULL;
                $user_profile->google = isset($data['google']) ? $data['google'] : NULL;
                $user_profile->facebook = isset($data['facebook']) ? $data['facebook'] : NULL;
                $user_profile->skype = isset($data['skype']) ? $data['skype'] : NULL;
                $user_profile->linkedin = isset($data['linkedin']) ? $data['linkedin'] : NULL;
                $user_profile->twitter = isset($data['twitter']) ? $data['twitter'] : NULL;
                $user_profile->website = isset($data['website']) ? $data['website'] : NULL;
                $user_profile->save($request->except(['user','email','educations','experiences']));

            $educations = $request->get('experiences');
            if (is_array($educations) || is_object($educations))
                {
                 foreach($educations as $e)
                    {
                        if($e['id']=='')
                        {
                            $education = new UserEducation();
                            $education->user_id = $user->id;
                            $education->qualification = $e['qualification'];
                            $education->college = $e['college'];
                            $education->university = $e['university'];
                            $education->passing_year = $e['passing_year'];
                            $education->percentage = $e['percentage'];
                            $education->save();
                        }
                        else
                        {
                            $education = UserEducation::find($e['id']);
                            $education->qualification = $e['qualification'];
                            $education->college = $e['college'];
                            $education->university = $e['university'];
                            $education->passing_year = $e['passing_year'];
                            $education->percentage = $e['percentage'];
                            $education->save();
                        }
                    }
                }
            $experiences=$request->get('educations');
            if (is_array($experiences) || is_object($experiences))
                {
                 foreach($experiences as $e)
                    {
                        if($e['id']=='')
                        {
                            $experience = new UserExperience();
                            $experience->user_id=$user->id;
                            $experience->company_name = $e['company_name'];
                            $experience->from = $e['from'];
                            $experience->to = $e['to'];
                            $experience->salary = $e['salary'];
                            $experience->reason = $e['reason'];
                            $experience->save();
                        }
                        else
                        {
                            $experience=UserExperience::find($e['id']);
                            $experience->company_name = $e['company_name'];
                            $experience->from = $e['from'];
                            $experience->to = $e['to'];
                            $experience->salary = $e['salary'];
                            $experience->reason = $e['reason'];
                            $experience->save();
                        }
                    }
                }
            Mail::send('auth.emails.user_activation', ['user_info'=>array($user->email,$pass)], function($message)use ($user) {
                                $message->to($user->email);
                                $message->subject('Thank You');
                });
            return response()->json(['success'=>true]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $people = People::find($id);
        $departments=Department::where('id','=',$people->department_id)->first();
        $designations=Designation::where('id','=',$people->designation_id)->first();
        $educations = UserEducation::where('user_id','=',$people->user_id)->get();
        $experiences = UserExperience::where('user_id','=',$people->user_id)->get();
        $projects = ProjectUser::where('user_id','=',$people->user_id)->get();
        return view('peoples.view',compact('people','projects','departments','designations','educations','experiences'));
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

    public function getPeople($id,Request $request)
    {
        $people = People::with('department','designation','user.projects')->findOrFail($id);
        $educations = UserEducation::where('user_id','=',$people->user_id)->get();
        $experiences = UserExperience::where('user_id','=',$people->user_id)->get();
        $departments=Department::all();
        $designations=Designation::all();
        return response()->json(array($people,$people->user->email,$educations,$experiences,$departments,$designations,$people->user->roles,$people->user->is_teamlead));
    }

    public function getFullName($id,Request $request)
    {
        $people = People::where('id',$id)->first();
        return response()->json($people);
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
        
            $data=$request->get('user_detail');            
             $people = People::findOrFail($id);
             $user = User::find($people->user_id);
             if(isset($data['email'])){
                $user->email = $data['email'];   
                 $user->save();
             }
             if(isset($request->roles)){
                 $user->roles =$request->roles;
                $user->save();
             }
             
            $user->is_teamlead =$request->get('is_teamlead');
            $user->is_projectlead =$request->is_projectlead;
            $user->save();
             $people->fname = $data['fname'] ? $data['fname'] : NULL;
             $people->lname = $data['lname'] ? $data['lname'] : NULL;
             
             $people->mobile = $data['mobile'] ? $data['mobile'] : NULL;
             $people->phone = $data['phone'] ? $data['phone'] : NULL;
             $people->dob = isset($data['dob']) ? $data['dob'] : NULL;
             $people->photo = isset($data['photo']) ? $data['photo'] : NULL;

             $people->marital_status = isset($data['marital_status']) ? $data['marital_status'] : NULL;
             $people->gender = isset($data['gender']) ? $data['gender'] : NULL;
             $people->adrs1 = isset($data['adrs1']) ? $data['adrs1'] : NULL;
             $people->adrs2 = isset($data['adrs2']) ? $data['adrs2'] : NULL;
             $people->city = isset($data['city']) ? $data['city'] : NULL;
             $people->state = isset($data['state']) ? $data['state'] : NULL;
             $people->zipcode = isset($data['zipcode']) && !empty($data['zipcode']) ? $data['zipcode'] : NULL;
             $people->country = isset($data['country']) ? $data['country'] : NULL;
             $people->pan_number = isset($data['pan_number']) ? $data['pan_number'] : NULL;
             $people->department_id = isset($data['department_id']) && !empty($data['department_id']) ? $data['department_id'] : 0;
             $people->designation_id = isset($data['designation_id']) && !empty($data['designation_id']) ? $data['designation_id'] : 0;
             $people->management_level = isset($data['management_level']) && !empty($data['management_level']) ? $data['management_level'] : '0';
             $people->join_date = isset($data['join_date']) ? $data['join_date'] : NULL;
             $people->google = isset($data['google']) ? $data['google'] : NULL;
             $people->facebook = isset($data['facebook']) ? $data['facebook'] : NULL;
             $people->skype = isset($data['skype']) ? $data['skype'] : NULL;
             $people->linkedin = isset($data['linkedin']) ? $data['linkedin'] : NULL;
             $people->twitter = isset($data['twitter']) ? $data['twitter'] : NULL;
             $people->website = isset($data['website']) ? $data['website'] : NULL;
             $people->save();

            
             $educations = $request->get('educations');
             
             if (is_array($educations) || is_object($educations))
                {
                     foreach($educations as $e)
                     {
                        if($e['id']=='')
                        {
                            $education = new UserExperience();
                            $education->user_id = $people->user_id;
                            $education->company_name = $e['company_name'];
                            $education->from = $e['from'];
                            $education->to = $e['to'];
                            $education->salary = $e['salary'];
                            $education->reason = $e['reason'];
                            $education->save();
                        }
                        else
                        {
                            $education = UserExperience::find($e['id']);
                            $education->company_name = $e['company_name'];
                            $education->from = $e['from'];
                            $education->to = $e['to'];
                            $education->salary = $e['salary'];
                            $education->reason = $e['reason'];

                            $education->save();
                        }
                     }
                }
             $experiences=$request->get('experiences');
             if (is_array($experiences) || is_object($experiences))
                {
                    foreach($experiences as $e)
                        {
                            if($e['id']=='')
                            {
                                $experience = new UserEducation();

                                $experience->user_id=$people->user_id;
                                $experience->qualification = $e['qualification'] != '' ? $e['qualification']  : null;
                                $experience->college = $e['college'] != "" ? $e['college'] : null;
                                $experience->university = $e['university'] != "" ? $e['university'] : null;
                                $experience->passing_year = $e['passing_year'] != '' ? $e['passing_year'] : null;
                                $experience->percentage = $e['percentage'] != '' ? $e['percentage'] : null;
                                
                                $experience->save();
                            }
                            else
                            {
                        
                                $experience=UserEducation::find($e['id']);
                                $experience->qualification = $e['qualification'] != '' ? $e['qualification']  : null;
                                $experience->college = $e['college'] != "" ? $e['college'] : null;
                                $experience->university = $e['university'] != "" ? $e['university'] : null;
                                $experience->passing_year = $e['passing_year'] != '' ? $e['passing_year'] : null;
                                $experience->percentage = $e['percentage'] != '' ? $e['percentage'] : null;
                                
                                $experience->save();
                            }
                        }
                }
            
         return response()->json(['success'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $people = User::find($id);
        $people->delete();
        return response()->json(['success'=>true]);
    }

    public function educationDestroy($id)
    {
        $education = UserEducation::find($id);
        $education->delete();

        return response()->json(['success'=>true]);
    }

    public function experienceDestroy($id)
    {
        $experience = UserExperience::find($id);
        $experience->delete();

        return response()->json(['success'=>true]);
    }

    /* to add people in project */

    public function getProjectPeople(Request $request,$id)
    {
        $allPeople =  ProjectUser::whereProjectId($id)->get();
        return view('peoples.addpeople',compact('allPeople'));
    }

    public function postProjectPeople(Request $request)
    {
        $project = Project::find($request->get('project_id'));
        $project->users()->sync($request->get('users') ?: []);
        return response()->json(['success'=>true]);
    }

    public function getPeopleofProject(Request $request,$id)
    {
        if ($id != 'Undefined') {
            $project = Project::find($id);
            $user_ids = $project->users->pluck('id');
            $project_users = $project->users()->where('user_id','!=',0)->with('people')->get();
            return response()->json(['user_ids'=>$user_ids,'project_users'=>$project_users]);
        }
    }

    public function updateProjectPeople(Request $request,$id)
    {
       //
    }

    public function destroyProjectPeople(Request $request)
    {
        //
    }
    public function updateUserProfilePhoto(Request $request)
    {
        $user = People::find(Auth::user()->people->id);
        $user->photo= $request->get('photo');
        $user->save();

    }
    public function changeStatus(Request $request){
         
        $user = User::find(explode('-', $request->id)[0]);
        
        if(explode('-', $request->id)[1] == 'active'){
            $user->active=true;
            $user->suspend =false;    
        }else{
            $user->active=false;
            $user->suspend =true;    
        }
        $user->save();
    }
}
