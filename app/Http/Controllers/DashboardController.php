<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Project;
use App\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $project_ids = '';
        if(Auth::user()->roles == 'admin')
        {

          $tasks=Task::where('completed',false)->where('created_at', '>=', Carbon::now()->subDays(5))->orderBy('created_at','desc')->get();  
        }
        else
        {
            $project_ids = Auth::user()->projects->pluck('id')->toArray();
            $tasks=Task::where('completed',false)->with('users')->whereHas('users', function($q){
                $q->where('user_id',Auth::user()->id);
              })->orderBy('created_at','desc')->get();  
        }
        return view('welcome',compact('tasks','project_ids'));
    }
     public function getTheme()
    {

       return view('theme.theme');
    }

}
