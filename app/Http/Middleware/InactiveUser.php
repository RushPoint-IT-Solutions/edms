<?php

namespace App\Http\Middleware;

use Closure;
use RealRashid\SweetAlert\Facades\Alert;

class InactiveUser
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
        if (auth()->check() && auth()->user()->status == 1)
        {
            auth()->logout();
            
            Alert::error('Your account has been deactivated. Please contact the Administrator');
            return back();
        }
        else
        {
            return $next($request);
        }
    }
}
