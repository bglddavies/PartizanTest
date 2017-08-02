<?php

namespace App\Http\Controllers;

use View;
use Log;

class HomepageController extends Controller
{
    public function index()
    {
        if(\Session::has('user'))
        {
            $user = \Session::get('user');
            if($user->user_role == 'back')
            {
                return \Redirect::to('/admin');
            }
            else if($user->user_role == 'front')
            {
                return \Redirect::to('/dashboard');
            }
        }
        return View::make('login.pages.welcome');
    }
}
