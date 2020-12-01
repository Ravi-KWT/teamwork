<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\TeamMember;
use App\Department;
use Validator;
use Response;

class TeamMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Data For teamlead dropdown
        $teamLeadIds = TeamMember::get()->pluck('teamlead_id')->toArray();
        $teamLeadIds=array_unique($teamLeadIds);
        if($teamLeadIds>0){
            $teamLeads = User::with('people')->where('is_teamlead',1)->whereNotIn('id',$teamLeadIds)->get()->pluck('people.name', 'id')->toArray();
        }
        else {
            $teamLeads = User::with('people')->where('is_teamlead',1)->get()->pluck('people.name', 'id')->toArray();
        }
        asort($teamLeads);


        // Data For Department/Team
        $departmentIds = TeamMember::get()->pluck('department_id')->toArray();
        if($departmentIds>0){
            $departments = Department::orderBy('name')->whereNotIn('id',$departmentIds)->get()->pluck('name', 'id')->toArray();
        }
        else{
            $departments = Department::orderBy('name')->get()->pluck('name', 'id')->toArray();
        }

        //Data Team Member Dropdown
        $membersIds = TeamMember::get()->pluck('member_id')->toArray();
        $membersIds=array_unique($membersIds);
        if($departmentIds>0){
            $members1 = User::with('people')->whereNotIn('id',[0,1])->whereNotIn('id',$membersIds)->where('is_teamlead',false)->get()->pluck('people.name', 'id')->toArray();
        }
        else{
            $members1 = User::with('people')->whereNotIn('id',[0,1])->where('is_teamlead',false)->get()->pluck('people.name', 'id')->toArray();
        }

        asort($members1);
        $teams = TeamMember::get()->pluck('department_id')->toArray();
        $deptIds=array_unique($teams);
        $departmentsList = Department::with('teamMembers.member.people')->whereIn('id',$deptIds)->get();
        return view('teams.index',compact('teamLeads','members1','departments','departmentsList'));
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
        $rules = [
            'department_id' => 'required',
            'teamlead_id' => 'required',
        ];

        $validator=Validator::make($request->all(),$rules);
        if ($validator->fails()) { 
            return Response::json(array('fail' => true,'errors' =>$validator->getMessageBag()->toArray()));
        }
        if(TeamMember::where('department_id',$request->get('department_id'))->count() > 0){
            return Response::json(array('fail' => true,'deptExistsError'=>'Team Already Created'));
        }
        if(TeamMember::where('teamlead_id',$request->get('teamlead_id'))->count() > 0){
            return Response::json(array('fail' => true,'teamLeadExistsError'=>'Team Lead Already assigned to other team/deparment'));
        }

        $members=$request->get('member_id');
        if(count($members)>0){
            foreach($members as $member){
                $team = new TeamMember;
                $team->department_id = $request->get('department_id');
                $team->teamlead_id = $request->get('teamlead_id');
                $team->member_id = $member;
                $team->save();
            } 
            $team = new TeamMember;
            $team->department_id = $request->get('department_id');
            $team->teamlead_id = $request->get('teamlead_id');
            $team->member_id = $request->get('teamlead_id');
            $team->save();
           
        }else{
            $team = new TeamMember;
            $team->department_id = $request->get('department_id');
            $team->teamlead_id = $request->get('teamlead_id');
            $team->member_id = $request->get('teamlead_id');
            $team->save();
        }
        // return Response::json(array('success'), 200);
         return Response::json(array('success' => true),200);
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

        $team =TeamMember::where('department_id',$id)->first();


            // Data For Department/Team
        $dept = Department::where('id',$id)->first();
        $departmentIds = TeamMember::get()->pluck('department_id')->toArray();
        if($departmentIds>0){
            $departments = Department::orderBy('name')->whereNotIn('id',$departmentIds)->get()->pluck('name', 'id')->toArray();
            $departments[$dept->id]=$dept->name;
        }else{
            $departments = Department::orderBy('name')->get()->pluck('name', 'id')->toArray();
            
        }

        $teamLeadIds = TeamMember::get()->pluck('teamlead_id')->toArray();

        $teamLeadIds=array_unique($teamLeadIds);

        if($teamLeadIds>0){
            $teamLeads = User::with('people')->where('is_teamlead',1)->whereNotIn('id',$teamLeadIds)->get()->pluck('people.name', 'id')->toArray();
             $teamLeads[$team->teamlead_id]=$team->teamlead->people->name;
        }
        else    {
            $teamLeads = User::with('people')->where('is_teamlead',1)->get()->pluck('people.name', 'id')->toArray();
        }
        asort($teamLeads);
        asort($departments);

        $teamLeadMembersIds = TeamMember::where('department_id',$id)->get()->pluck('member_id')->toArray(); 

        $allMembersIds = TeamMember::whereNotIn('member_id',$teamLeadMembersIds)->get()->pluck('member_id')->toArray();

        $membersIds=array_unique($allMembersIds);

        if($membersIds>0){
            $allMembers = User::with('people')->where('is_teamlead',false)->whereNotIn('id',[0,1])->whereNotIn('id',$membersIds)->get()->pluck('people.name', 'id')->toArray();
        }
        else{
            $allMembers = User::with('people')->where('is_teamlead',false)->whereNotIn('id',[0,1])->get()->pluck('people.name', 'id')->toArray();
        }
        
        asort($allMembers);

        return Response::json(['team'=>$team,'departments'=>$departments,'teamLeads'=>$teamLeads,'allMembers'=>$allMembers,'teamMembers'=>$teamLeadMembersIds]);
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
      $rules = [
            'department_id' => 'required',
            'teamlead_id' => 'required',
        ];

        $validator=Validator::make($request->all(),$rules);
        if ($validator->fails()) { 
            return Response::json(array('fail' => true,'errors' =>$validator->getMessageBag()->toArray()));
        }

        $team=TeamMember::where('department_id',$id)->delete();
        $members=$request->get('member_id');
        if(count($members)>0){
            foreach($members as $member){
                $team = new TeamMember;
                $team->department_id = $request->get('department_id');
                $team->teamlead_id = $request->get('teamlead_id');
                $team->member_id = $member;
                $team->save();
            }    
            $team = new TeamMember;
            $team->department_id = $request->get('department_id');
            $team->teamlead_id = $request->get('teamlead_id');
            $team->member_id = $request->get('teamlead_id');
            $team->save();
        }else{
            $team = new TeamMember;
            $team->department_id = $request->get('department_id');
            $team->teamlead_id = $request->get('teamlead_id');
            $team->member_id = $request->get('teamlead_id');
            $team->save();
        }
        

        // return Response::json(array('success'), 200);
         return Response::json(array('success' => true),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         TeamMember::where('department_id',$id)->delete();
         return Response::json(array('success' => true),200);
    }
}
