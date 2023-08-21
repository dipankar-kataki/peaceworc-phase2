<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            
            if( (!Auth::check()) && ($request->segment(1) == 'admin') ){
                return route('web.login');
            }
            else if (!Auth::check()){
                return route('login-expire');
            }else{
                return route('web.landing');
            }
        }
    }
}
