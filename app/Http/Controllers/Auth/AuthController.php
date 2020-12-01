<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\UserToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Session\Middleware\StartSession;
use Session;
use Response;
use Validator;



class AuthController extends Controller
{
    
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */

    protected $redirectTo = '/';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
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
