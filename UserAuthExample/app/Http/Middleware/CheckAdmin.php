<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request. Check that requester has administrative permissions
     * Return a redirect to the login page if the user does not have administrative permissions
     * THIS NEEDS TO BE CHANGED TO A ROLE RULESET to allow DIFFERENT LEVELS OF ADMINISTRATIVE PERMISSIONS
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
            if($user->user_role == 'back')
            {
                \View::share('user',$user);
                $dt = new \DateTime();
                $dt->modify('-1 week');
                $dtf = $dt->format('Y-m-d H:i:s');
                $sl = \DB::table('successful_login')
                        ->where('user_id', $user->id)
                        ->where('dt', '>=', $dtf)
                        ->orderBy('dt', 'DESC')
                        ->get();

                \View::share('loginItems', $sl);
                return $next($request);
            }
        }
        return \Redirect::to('/');
    }
}