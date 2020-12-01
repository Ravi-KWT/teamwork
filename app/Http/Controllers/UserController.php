<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\People;
use App\Department;
use App\Designation;
use Auth;
use Hash;
use Redirect;
use Former\Facades\Former;
use Illuminate\Support\Facades\Input;
use Validator;
use Carbon\Carbon;
use App\UserToken;
class UserController extends Controller
{
    public function changePassword()
    {

    	return view('change-password');
    }

    public function updatePassword(Request $request)
    {
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {

            return Hash::check($value, current($parameters));

        });
         //set validation for each request
        $this->validate($request, [
            'oldpassword'=>'required|old_password:'.Auth::user()->password,
            'newpassword'=>'required|min:3|different:oldpassword|same:password_confirmation',
            'password_confirmation' => 'required|min:3'
        ],[
            //  set custom message for validation rule if not set it will display default error message
            'oldpassword.old_password'=>"You have entered an invalid password.",

            'oldpassword.required'=>'This field is required.',
            'newpassword.required'=>'This field is required.',
            'password_confirmation.required'=>'This field is required.',
            'newpassword.different'=>'You have entered old and new password is same, Please try different.',
            'newpassword.same'=>'The new password and confirmation password do not match.',
        ]);

        $user=Auth::user();
        $credentials = [
            'email' => Auth::user()->email,
            'password' => $request->get('oldpassword'),
        ];

        if(Auth::validate($credentials))
        {

            $user->password = bcrypt($request->get('newpassword'));
            $user->save();
            return redirect()->back()->with('success','Your password is successfully changed.');

        }
        else
        {
            return redirect()->back()->with('message','Invalid Current Password')->withInput($credentials);
        }



    }

    public function getAccount()
    {
        $user = People::find(Auth::user()->people->id);
        Former::populate($user);
        return view('auth/change-profile',compact('user'));
    }

    public function postAccount(Request $request)
    {

         $this->validate($request, [
            'fname'=>'required',
            'facebook'=>'url',
            'twitter'=>'url',
            'website' =>'url',
            'google'  =>'url',
            'linkedin'=>'url',
            'phone' => 'numeric',
            'mobile' => 'numeric',
        ],
        [
            'fname.required'=>"Please enter First Name",
            'google.url' => "Invalid url",
            'facebook.url' => "Invalid url",
            'linkedin.url' => "Invalid url",
            'twitter.url' => "Invalid url",
            'website.url' => "Invalid url",

        ]);

        $user = People::find(Auth::user()->people->id);
        $user->update($request->all());
        return redirect('/');

    }

    public function getUserList(){
        $users = User::where('id','<>','0')->get();

        return view('users.index',compact('users'));
    }
    public function postUserPermission(Request $request){
        $id = $request->get('pdata')[0];
        $roles = $request->get('pdata')[1];
        $is_teamlead = $request->get('pdata')[2];
        $is_viewer = $request->get('pdata')[3];
        $active = $request->get('pdata')[5];
        
        $status_text = $request->get('pdata')[7];
        
        $userData = User::find($id);
        $userData->roles = $roles;
        $userData->is_teamlead = $is_teamlead;
        $userData->is_viewer = $is_viewer;
        // $userData->active = $active;

        if($status_text =='Active'){
             $userData->active = true;    
                $userData->suspend = false;    
        }
        if($status_text =='Inactive'){
             $userData->active = false;    
        }
        if($status_text =='Suspend'){
             $userData->suspend = true;    
        }
        $userData->save();
        return response()->json(array('success'=>true));
    }

    public function getBirthdayList(){
        $users = User::with('people')->where('id','<>','0')->where('active',true)->whereHas('people',function($q){
           $q->whereMonth('dob', '=', date('m'));
        })->get();

        return view('users.birthday_list',compact('users'));
    }
    //notification token store
    public function userToken(Request $request){
        $user_token = UserToken::where('fcm_token','=',$request->token)->first();
        if (!$user_token) {
            $user_token = new UserToken;
        }
        
        $user_token->user_id = Auth::id();
        $user_token->fcm_token = $request->token;
        $user_token->save();
        return response()->json(202);
    }
}
