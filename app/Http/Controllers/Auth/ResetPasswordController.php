<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use DB;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = "/";

    public function reset(Request $request)
    {
        // validate the fields
        $rules = [
            '_token' => 'required',
            'password' => 'required|confirmed|min:6|max:16',
            'password_confirmation' => 'required'
        ];
        $validator = Validator::make($request->all() ,$rules);

        // if validation failed then it will redirect back
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$tokenData)
        {
            return redirect()->back()->withErrors(['email' => 'Token expired please try again.']);
        }

        // find the user
        $user = User::where('email', '=',$request->email)->first();

        if (!$user)
        {
            return redirect()->back()->withErrors(['email' => 'Email not found please try again.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $user->email)
        ->delete();

        // make user login
        Auth::login($user, true);
        return redirect()->route('home');
    }
}
