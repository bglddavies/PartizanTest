<?php

namespace App\Http\Middleware;

use Closure;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(\Session::has('user'))
        {
            $user = \Session::get('user');
            if($user->user_role == 'front')
            {
                return $next($request);
            }
        }
        return \Redirect::to('/');
    }
}