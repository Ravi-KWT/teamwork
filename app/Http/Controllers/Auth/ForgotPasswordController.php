<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\ForgetPassword;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Str;
use Response;
use Validator;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    // code for forgot password method over ride
    public function sendResetLinkEmail(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if (!$user)
        {
            return response()->json(array('error'=>true , 'msg'=>trans("We can't find a user with that e-mail address.")));
        }

        $token = Str::random(60);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $response = Mail::to($user->email)->send(new ForgetPassword($user, $token));
        return response()->json(array('success' => true ,'msg' => trans('We have e-mailed your password reset link!')));
    }
}
