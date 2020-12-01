<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectCategory;
use App\User;
use App\People;
use App\Company;
use Illuminate\Support\Facades\Input;
use Redirect;
use Former\Facades\Former;
use Validator;
use Image;
use App\ProjectUser;
use App\LogTime;
use Auth;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response

     */

    public function index()
    {
    
        $companies = Company::all();
        $pm_lists = User::with('people')->where('is_projectlead','=',true)->get();

         if(Auth::user()->roles == 'admin')
        {
            $projects = Project::where('status','active')->with('company','latestLogs')->get()->groupBy('client_id');
        }
        else
        {
            $projects = Project::whereHas('users',function($q){
                $q->where('user_id',Auth::user()->id);
            })->where('status','active')->with(['company','users.people','latestLogs'])->get()->groupBy('client_id');
        }
        $projectsCategories = ProjectCategory::all();
        return view('projects.index',compact('companies','projects','projectsCategories','pm_lists'));
    }

    public function getProjects()
    {
        $projects='';

        if(Auth::user()->roles == 'admin')
        {
            $projects = Project::where('status','active')->with('company','latestLogs')->get()->groupBy('client_id');
        }
        else
        {
            $projects = Project::whereHas('users',function($q){
                $q->where('user_id',Auth::user()->id);
            })->where('status','active')->with(['company','users.people','latestLogs'])->get()->groupBy('client_id');
        }
       $projectsCategories = ProjectCategory::all();
       return response()->json(array('projects'=>$projects,'projectsCategories'=>$projectsCategories));
    }

    public function postProjects(Request $request){

        $search_data = $request->search_data;
        
            $companies = Company::all();   

        $data  = explode('kwt', trim(strtolower($request->search_data)));
        $project_id = count($data) > 1 ? $data[1] : 0; 

         if(Auth::user()->roles == 'admin')
        {
            if($search_data != ""){
                if ($data) {
                    $projects = Project::where('name','ilike','%'.trim(strtolower($search_data)).'%')->orWhere('id','=',$project_id)->where('status','active')->with('company','latestLogs')->get()->groupBy('client_id');
                }
                else{

                    $projects = Project::where('name','ilike','%'.trim(strtolower($search_data)).'%')->where('status','active')->with('company','latestLogs')->get()->groupBy('client_id');
                }
            }
            else{
                $projects = Project::where('status','active')->with('company','latestLogs')->get()->groupBy('client_id');
            }
        
        }
        else
        {
            if ($search_data != "") {
                if ($data) {
                    
                    $projects = Project::where('name','ilike','%'.trim(strtolower($search_data)).'%')->orWhere('id','=',$project_id)->where('status','active')->whereHas('users',function($q){
                        $q->where('user_id',Auth::user()->id);
                    })->where('status','active')->with(['company','users.people','latestLogs'])->get()->groupBy('client_id');   
                }
                else{

                    $projects = Project::where('name','ilike','%'.trim(strtolower($search_data)).'%')->where('status','active')->whereHas('users',function($q){
                        $q->where('user_id',Auth::user()->id);
                    })->where('status','active')->with(['company','users.people','latestLogs'])->get()->groupBy('client_id');
                }
            }
            
            else{

                $projects = Project::where('status','active')->whereHas('users',function($q){
                    $q->where('user_id',Auth::user()->id);
                })->where('status','active')->with(['company','users.people','latestLogs'])->get()->groupBy('client_id');
            }
        }
        $projectsCategories = ProjectCategory::all();
        $pm_lists = User::where('is_projectlead','=',true)->get();
        return view('projects.index',compact('companies','projects','projectsCategories','pm_lists'));
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
        
        $input= $request->all();

        $projects=Project::create($input);
        $projects->save();

        $users = User::where('roles','admin')->pluck('id')->toArray();
        if(!empty($users))
        {
            foreach ($users as $key => $value) {
                $projectUser = new ProjectUser;
                $projectUser->project_id = $projects->id;
                $projectUser->user_id = $value;
                $projectUser->save();
            }
        }
        $projectUser = new ProjectUser;
        $projectUser->project_id = $projects->id;
        $projectUser->user_id = $request->projectlead_id;
        $projectUser->save();
        
            
        return response()->json(['success'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return redirect('/404');
        $project = Project::find($id);
        return view('projects.view',compact('project'));
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

    public function getProject($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
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

        $project = Project::find($id);
        $project->update($request->all());  
        return response()->json(['success'=>true] );      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();    
        return response()->json(['success'=>true]);
    }
    public function getProjectList($status){
        if(in_array($status,['active','archive','completed','onhold']))    {
            $projects = Project::where('status',$status)->get();
            return view('projects.'.$status,compact('projects'));
        }
    }
 
    public function postChangeProjectStatus(Request $request){
            $project = Project::find($request->id);
            $project->status = $request->status;
            $project->save();
            return Response::json(['success'=>true,'re'=>$project]);    
    }

    //get current projects with project manager 
    public function getCurrentProjects(){
        $projects = Project::with('projectManager')->where('status','active')->get();
        return view('projects.current_projects_lists',compact('projects'));
    }
}
