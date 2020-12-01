<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProjectCategory;
use App\Project;
use Illuminate\Support\Facades\Input;
use Redirect;
use Former\Facades\Former;
use Validator;
use Image;
use App\Company;
use Auth;

class ProjectCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('project_categories.index');
    }

    public function getProjectCategories()
    {
       $categories = ProjectCategory::get();
       return response()->json($categories);
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
            $categories=ProjectCategory::create($input);
            $categories->save();
            // return Redirect::route('project-categories.index')->with("success","Record Save");
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
        $project_category = ProjectCategory::find($id);
        if(empty($project_category))
        {
            return redirect('/404');
        }
        // $variable = Project::with('project-category')->whereCategoryId($id)->get();
        if(Auth::user()->roles == 'admin')
        {
            $projects=Project::where('status','active')->with('category','company')->where('category_id',$id)->get();  
        }
        else
        {
            $projects=Project::where('status','active')->with('category','company')->where('category_id',$id)->whereHas('users', function($q){
                $q->where('user_id',Auth::user()->id);
            })->get(); 
        }
        
        // $projects = Project::where('category_id','=',$project_category->id)->get();

        return view('project_categories.view',compact('project_category','projects'));
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

    public function getCategory($id)
    {
        $category = ProjectCategory::findOrFail($id);
        return response()->json($category);
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
         $category = ProjectCategory::find($id);
         $category->name = $request->get('name');
         $category->save();
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
        $project_category = ProjectCategory::find($id);
        $project_category->delete();

        return response()->json(['success'=>true]);
    }
}
