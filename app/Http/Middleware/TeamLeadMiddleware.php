<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Auth;
class TeamLeadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
      public function handle($request, Closure $next)
    {
        if(Auth::check())
        {
            if(Auth::user()->roles=='admin' ||  Auth::user()->is_teamlead == true)
            {
                return $next($request);
            }
            else
            {
               return redirect()->intended('/')->with('info','You do not have rights to access this location'); 
            }
            
        }

        return redirect('/login');
        
    }
}
