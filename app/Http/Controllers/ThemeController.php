<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserTheme;
use Illuminate\Support\Facades\Input;
use Auth;
use Redirect;


class ThemeController extends Controller
{

    public function changeTheme(Request $request)
    {
            $theme = UserTheme::where('user_id', Auth::user()->id)->first();
            if(empty($theme))
            {
                $theme = new UserTheme;
                $theme->user_id = Auth::user()->id;
                $theme->class = Input::get('name');  
            }
            else
            {
                $theme->class = Input::get('name');
            }
            $theme->save();
            
            return response()->json(['success'=>true, 'class_name' => Input::get('name')]);
    }

}

