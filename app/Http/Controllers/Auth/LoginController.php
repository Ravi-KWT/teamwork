<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\UserToken;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Session;
use Response;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        if (Auth::validate(['email' => $request->email, 'password' => $request->password, 'active' => 0])) {
            return response()->json(array('error'=>true , 'msg'=>'Your account is Inactive or not verified'));
        }

        if (Auth::validate(['email' => $request->email, 'password' => $request->password, 'suspend' => 1])) {
            return response()->json(array('error'=>true , 'msg'=>'Your account has been suspended'));
        }

        $credentials  = array('email' => $request->email, 'password' => $request->password);
        if (Auth::attempt($credentials, $request->has('remember_me'))){
            return response()->json(array('success'=>true));
            
        }
        return response()->json(array('error'=>true,'msg'=>'Incorrect email address or password'));
            
    }

    public function logout(Request $request) 
    {
        $user_token = UserToken::where('fcm_token','=',$request->fcm_token)->first();
        if ($user_token) {
            $user_token->delete();
        }
    
      Auth::logout();
      session_unset();
      return redirect('/login');
    }
}
