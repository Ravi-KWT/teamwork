<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Company;
use App\Project;
use App\TaskCategory;
use App\ProjectCategory;
use App\Industry;
use App\Milestone;
use App\ProjectUser;
use Carbon\Carbon;
use Redirect;
use Session;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('shared.company_list', function($view)
        {
          
             $projects='';
            // $companies = Company::all(); 


            if(Auth::user()->roles == 'admin')
            {
                $projects = Project::where('status','active')->with('company')->orderBy('name')->get()->groupBy('client_id');
                $companies_ids= Project::distinct('client_id')->where('status','active')->with('company')->pluck('client_id')->toArray();

                $companies = Company::whereIn("id",$companies_ids)->orderBy('name')->get();
                // dd($companies);
            }
            else
            {
                $projects = Project::whereHas('users',function($q){
                    $q->where('user_id',Auth::user()->id);
                })->with('company')->where('status','active')->orderBy('name')->get()->groupBy('client_id');

                $companies_ids= Project::distinct('client_id')->whereHas('users',function($q){
                    $q->where('user_id',Auth::user()->id);
                })->where('status','active')->with('company')->pluck('client_id')->toArray();
                $companies = Company::whereIn("id",$companies_ids)->orderBy('name')->get();
            }

            $view->with('companies', $companies);
            $view->with('current_user', Auth::user());
            $view->with('projects', $projects);

            // $projects='';
            
            // // $companies = Company::all(); 

            // if(Auth::user()->roles == 'admin')
            // {
            //     $projects = Project::where('status','active')->with('company')->orderBy('name')->get()->groupBy('client_id');
            // }
            // else
            // {
            //     $projects = Project::whereHas('users',function($q){
            //         $q->where('user_id',Auth::user()->id);
            //     })->with('company')->orderBy('name')->get()->groupBy('client_id');
            // }

            // $view->with('companies', Company::orderBy('name','asc')->get());
            // $view->with('current_user', Auth::user());
            // $view->with('projects', $projects);
        });

        view()->composer('shared.header', function($view)
        {
            $billable_hours = 0;
            $non_billable_hours = 0;
            if(\Request::segment(1)=='projects' && \Request::segment(2))
            {
                $project = Project::where('id',\Request::segment(2))->first();
                
                // if(empty($project))
                // {
                //     return Redirect::to('/404')->send();
                // }
                if($project->tasks)
                {
                    $tasks = $project->tasks;
                    foreach($tasks as $task)
                    {
                        $billable_hours += $task->logtimes->where('billable', true)->sum('hour');
                        $non_billable_hours += $task->logtimes->where('billable', false)->sum('hour');
                    }
                }
            }
            $view->with('billable_hours', $billable_hours);
            $view->with('non_billable_hours', $non_billable_hours);

        });

        // view()->composer('shared.project_list', function($view)
        // {
        //     $view->with('project_categories', ProjectCategory::all());
        //     $view->with('projects',Project::all());
        // });

        view()->composer('shared.industry_list', function($view)
        {

            $view->with('projects', Project::all());
            $view->with('companies', Company::all());
            $view->with('industries', Industry::all());
        });

        view()->composer('shared.project_detail', function($view)
        {
            $view->with('projects', Project::where('status','active')->get());
            $view->with('project_categories', ProjectCategory::all());
            $view->with('project_users', ProjectUser::all());


            $view->with('task_categories',TaskCategory::whereHas('tasks',function($q){
                // $q->where('project_id',\Request::segment(2))->where('completed','false');
                if(Auth::user()->roles!='admin'){
                    $q->where('project_id',\Request::segment(2))->whereHas('users',function($q){
                       $q->where('user_id',Auth::user()->id);
                    });    
                }else{
                     $q->where('project_id',\Request::segment(2))->where('completed','false');
                }
                // $q->where('project_id',\Request::segment(2))->whereHas('users',function($q){
                //    $q->where('user_id',Auth::user()->id);
                // });
            })->where('project_id',\Request::segment(2))->orderBy('name')->get());



            if(Auth::user()->roles=='admin'){
                $projects=Project::where('id',\Request::segment(2))->first();    
            }else{
                $projects=Project::where('status','active')->where('id',\Request::segment(2))->first();
            }
            
            if(empty($projects))
            {
                return redirect('/')->with('error','The project that you want to access does not exist or deleted by Admin.');
            }
            $view->with('loggedUserproject',$projects);
            $tasks = '';
            if(Auth::user()->roles=='admin')
            {
                $tasks =  Task::where('project_id',\Request::segment(2))->with('users.people')->get();   
            }
            else
            {
                $tasks =  Task::where('project_id',\Request::segment(2))->where('completed',false)->whereHas('users', function($q){
                        $q->where('user_id','0');
                    })->orWhereHas('users', function($q){
                        $q->where('user_id',Auth::user()->id);
                    })->with('users.people')->get();  
            }

            $view->with('tasks',$tasks);

        });

        view()->composer('shared.project_list', function($view)
        {

            // $view->with('project_categories', ProjectCategory::whereHas('projects',function($q){
            //     $q->where('status','active');
            // })->orderBy('name')->get());

            $projectsList =  Project::where('status','active')->whereHas('users',function($q){
                $q->where('user_id',Auth::user()->id);
            })->with('category')->get()->pluck('category.name','category.id')->toArray();
            $view->with('project_categories',$projectsList);
            $projects = '';
            if(Auth::user()->roles=='admin')
            {
                $projects = Project::where('status','active')->get();
            }
            else
            {
                $projects =  Project::where('status','active')->whereHas('users', function($q){
                    $q->where('user_id',Auth::user()->id);
                })->get(); 
            }
            $view->with('projects',$projects);

        });

        view()->composer('shared.milestones', function($view)
        {
            $view->with('projects', Project::get());
            $view->with('project_categories', ProjectCategory::all());
            $view->with('milestones', Carbon::now());
        });

        view()->composer('shared.left_sidebar', function($view)
        {
            $view->with('current_user', Auth::user());
        });

        view()->composer('shared.task_detail_sidebar', function($view)
        {
            $task = Task::find(\Request::segment(4));
            if(empty($task))
            {
                return redirect('/404')->send();
            }
            $view->with('task_details', Task::where('id',\Request::segment(4))->with('category','users.people')->first());
        });

        // View::composer('*', function($view)
        // {
        //     $view->with('current_user', Auth::user());
        // });



    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
