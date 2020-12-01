<?php

namespace App\Http\Middleware;

use Closure;
use Alert;
use Auth;
class StatusMiddleware
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
        // if(Auth::user()->role != 'admin' || !Auth::user()->role != 'super-admin')
        // {
        //     switch (Auth::user()->active) {
        //         case 'false':
        //             Auth::logout();
                   
        //             return redirect()->to('/login')->with('Your acount is inctive or in varification');

        //             return $next($request);
        //     }
            
        // }
       if ($request->user()->inactive) {
            Auth::logout();
            return redirect('/login')->with('error', 'Your acount is not verified');
            // return redirect('/logout')->with('error','Your acount is inctive or in varification');
        }
        if ($request->user()->suspend ){
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is suspended');
            // return redirect('/logout')->with('error','Your account has been suspended');
        }
        return $next($request);
    }
}



            

            
