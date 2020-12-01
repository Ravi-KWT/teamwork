<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Excel;
use App\Project;
use App\TaskCategory;
use App\Task;


class TaskCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects=Project::all();
        $task_categories = TaskCategory::with('tasks','project')->get();
        return view('task_categories.index',compact('task_categories','projects'));   
    }
    
    public function getTaskCategories()
    {
       $projects=Project::all();
       $categories = TaskCategory::with('tasks','project')->get();
       
       return response()->json(['categories'=>$categories,'projects'=>$projects]);
    }

    public function postImportExcel()
    {

        if(Input::hasFile('import_file')){
            $extension = Input::file('import_file')->getClientOriginalExtension();
            $size = Input::file('import_file')->getSize();
            if($extension == 'xls' || $extension == 'xlsx' || $extension == 'csv')
            {
                if($size <= 1000000)
                {
                    $path = Input::file('import_file')->getRealPath();
                    $data = Excel::load($path, function($reader) {
                    })->get();
                    if(!empty($data) && $data->count()){
                        foreach ($data as $key => $value) {
                            $insert[] = ['name' => $value->tasklist];
                            $category = new TaskCategory;
                            $category->project_id = Input::get('project_id');
                            $category->name = $value->tasklist;
                            $category->save();
                        }

                        return back()->with('success','Task list successfully uploaded.');
                    }  
                }
                else
                {
                   return back()->with('error','File size exceed, please upload file less than 10 mb.');  
                }
            }
            else
            {
               return back()->with('error','please upload valid file(.xls or .xlsx or .csv)'); 
            }
        }
        else
        {
          return back()->with('error', 'Please upload file to import task list.');  
        }
        return back();
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
        $input= Input::all();

        $categories=TaskCategory::create($input);
        $categories->save();
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
         $task_category = TaskCategory::find($id);
         if(empty($task_category))
         {
            return redirect('/404');
         }
        
          $project_id = $task_category->project_id;
          $tasks = '';
            if(Auth::user()->roles=='admin')
            {
                $tasks =  Task::with('category')->where('category_id',$id)->where('project_id',$project_id)->where('completed',false)->with('project')->orderBy('created_at','desc')->get();
                $tasksCompleted=Task::with('category')->where('category_id',$id)->where('project_id',$project_id)->where('completed',true)->with('project')->orderBy('created_at','desc')->get();

            }
            else
            {
                $tasks =  Task::with('category')->where('category_id',$id)->where('project_id',$project_id)->where('completed',false)->whereHas('users', function($q){
                        $q->where('user_id',Auth::user()->id)->orWhere('user_id','0');
                     })->with('project','users.people')->orderBy('created_at','desc')->get();  
                $tasksCompleted =  Task::with('category')->where('category_id',$id)->where('project_id',$project_id)->where('completed',true)->whereHas('users', function($q){
                        $q->where('user_id',Auth::user()->id)->orWhere('user_id','0');
                    })->with('project','users.people')->orderBy('created_at','desc')->get();  
            }
            return view('task_categories.view',compact('tasksCompleted','tasks','task_category','id','project_id'));
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

    public function getTaskCategory($id)
    {
        $task_category = TaskCategory::findOrFail($id);
        return response()->json($task_category);
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
         $category = TaskCategory::find($id);
         $category->name = Input::get('name');
         $category->project_id = Input::get('project_id');
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
        $task_category = TaskCategory::find($id);
        $task_category->delete();

        return response()->json(['success'=>true]);
    }

    public function getUsers(Request $request)
    {
        $category_id = $request->get('category_id');
        $category = TaskCategory::find($category_id);
        $project = Project::find($category->project_id);
        $projectUsers = $project->users()->with('people')->get();
        $loginUser = Auth::user();
        return response()->json(array('users'=>$projectUsers,'loginUser'=>$loginUser->people));
    }

    public function addTask(Request $request)
    {
        $tasks=Task::create(Input::all());
        $tasks->users()->sync($request->get('user_id') ? $request->get('user_id') : [0]);
        $tasks->completed = false;
        if(Auth::user()->people->lname)
            $tasks->assignedby = Auth::user()->people->fname.' '.Auth::user()->people->lname ;
        else
            $tasks->assignedby = Auth::user()->people->fname;

        $tasks->save();
   
        return response()->json(['success'=>true]);
    }

    //Add task category from project 
    public function addTaskCategory(Request $request){
        
        $category = new TaskCategory;
        $category->project_id = $request->project_id;
        $category->name = $request->name;
        $category->save();
        return response()->json(['success','true']);
    }

}
