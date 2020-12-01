<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Milestone;
use App\User;
use App\Project;
use App\People;
use Redirect;
use Carbon\Carbon;

class MilestonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    { 
        $time = Carbon::now();
        $projects=Project::all();
        $users=People::with('user')->orderBy('fname')->get();
        $milestone = Milestone::find($id);

        return view('milestones/index',compact('projects','id','users','time','milestone'));
    }


    public function getMilestones(Request $request)
    {
        $project = Project::find($request->get('project_id'));
        $milestones =  Milestone::whereProjectId($request->get('project_id'))->with('users.people')->get();
        $users=$project->users()->where('user_id','!=',0)->with('people')->orderBy('email')->get();         
        //$milestones = Milestone::find_by_project_id($pId)->get();
        return response()->json(array('milestones'=>$milestones,'users'=>$users)); 
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

        $milestones=Milestone::create($request->all());
        $milestones->users()->sync($request->get('user_id') ? $request->get('user_id') : [0]);
        $milestones->save();
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
        //
    }

    public function getMilestone($id)
    {
        $milestone = Milestone::findOrFail($id);
        
        $milestone_users=array();
        foreach ($milestone->users as  $u) {
            if($u->id != 0)
            {
               array_push($milestone_users, $u->people); 
            }
        }
        $allUsers=User::with('people')->where('id','!=',0)->orderBy('email')->get();
        return response()->json(array('milestone'=>$milestone,'milestone_users'=>$milestone_users,'allUsers'=>$allUsers));
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
        $milestone = Milestone::find($request->get('id')); 
        $milestone->users()->sync($request->get('user_id') ? $request->get('user_id') : []);
        $milestone->update($request->all());
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
        $milestone = Milestone::find($id);
        $milestone->delete();    
        return response()->json(['success'=>true]);
    }
    public function postMilestoneStatus(Request $request)
    {

        $milestone = Milestone::find($request->get('id'));
        $milestone->completed = $request->get('completed')== 'true' ? false : true;
        $milestone->save();
        return response()->json(['success'=>true,'status'=>$milestone->completed]);
    }
}
