<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SuccessfulLogin;
use App\Models\User;
use App\Models\PasswordReset;
use App\Models\LoginAttempt;
use App\Models\UserInvite;
use Illuminate\Http\Request as Request;

class UserAuthController extends Controller
{
    private function isLoggedIn()
    {
        if(\Session::has('user'))
        {
            return true;
        }
        return false;
    }

    private function isUser($id)
    {
        if($this->isLoggedIn())
        {
            $user = \Session::get('user');
            if((isset($user->id)) && ($user->id == $id))
            {
                return true;
            }
        }
        return false;
    }

    private function isAdmin()
    {
        if($this->isLoggedIn())
        {
            $user = \Session::get('user');
            $userM = User::where('id', $user->id)->first();
            if($userM->user_role == 'back')
            {
                return true;
            }
        }
        return false;
    }

    private function login($request)
    {
        if(!$this->isLoggedIn()) {
            $input = \Input::all();
            $validator = \Validator::make(
                $input,
                ['email' => 'required|email', 'password' => 'required']
            );
            if (!$validator->fails()) {
                $user = User::where('email', $input['email'])->where('activated', 1)->first();
                if ($user) {
                    if(LoginAttempt::canRelog($user->id))
                    {
                        if (\Hash::check($input['password'], $user->password)) {
                            \Session::put('user', $user);
                            LoginAttempt::clearAttempts($user->id);

                            $sl = new SuccessfulLogin;

                            $ua = $request->header('User-Agent');
                            $ip = $request->ip();
                            $dt = new \DateTime();
                            $df = $dt->format('Y-m-d H:i:s');
                            $user_id = $user->id;

                            $sl->user_id = $user_id;
                            $sl->ip = $ip;
                            $sl->user_agent = $ua;
                            $sl->dt = $df;

                            $sl->save();

                            return true;
                        }
                        else
                        {
                            $now = new \DateTime();
                            $nf = $now->format('Y-m-d H:i:s');
                            $la = new LoginAttempt;
                            $la->user_id = $user->id;
                            $la->created_at = $nf;
                            $la->save();
                            return ['Could not find a user with this username or password.'];
                        }
                    }
                    else
                    {
                        return ['This account has been locked. Try again in 1 hour or contact administration.'];
                    }
                }
                else
                {
                    return ['Could not find a user with this username or password.'];
                }
            }
            else
            {
                $errors = $validator->errors();
                return $errors->all();
            }
        }
        else
        {
            return ['You are already logged in.'];
        }

        return false;
    }

    /**
     * todo: MAKE SET SECRETS REQUIRE PASSWORD TO VERIFY
     *
     */

    private function setSecrets()
    {
        if($this->isLoggedIn())
        {
            $input = \Input::all();
            $validator = \Validator::make(
                $input,
                ['email' => 'required|email', 'a'=>'required|min:5|max:15|regex:/(^[A-Za-z0-9 ]+$)+/', 'q'=>'required|min:5|max:15|regex:/(^[A-Za-z0-9 ]+$)+/']
            );

            if(!$validator->fails())
            {
                $user = \Session::get('user');
                $userModal = User::where('id', $user->id)->where('activated', 1)->first();
                if($userModal)
                {
                    $userModal->secret_question = $input['q'];
                    $userModal->secret_answer = bcrypt($input['a']);
                    $userModal->save();

                    return true;
                }
            }
        }

        return false;
    }

    private function reqPasswordReset()
    {
        $input = \Input::all();
        $validator = \Validator::make(
            $input,
            ['email' => 'required|email', 'answer'=>['required','min:5', 'max:15', 'regex:/(^[A-Za-z0-9 ]+$)+/']]
        );

        if(!$validator->fails())
        {
            if($this->checkSecrets($input['email'], $input['answer']) === true)
            {
                //DO THE PASSWORD RESET FUNCTION
                $token = bin2hex(openssl_random_pseudo_bytes(4));
                $dt = new \DateTime();
                $dtf = $dt->format('Y-m-d H:i:s');

                PasswordReset::deleteByEmail($input['email']);

                $pwr = new PasswordReset();
                $pwr->email = $input['email'];
                $pwr->token = $token;
                $pwr->created_at = $dtf;

                $pwr->save();

                //SEND THE PASSWORD RESET CONFIRMATION
                $this->sendPasswordReset($pwr);
                return true;
            }
            else
            {
                return ['Secret answer not recognised.'];
            }
        }
        else
        {
            $errors = $validator->errors();
            return $errors->all();
        }
    }

    private function checkSecrets($email, $a)
    {
        $validator = \Validator::make(
            ['email' => $email, 'answer' => $a],
            ['email' => 'required|email', 'answer'=>'required|min:5']
        );

        if(!$validator->fails())
        {
            $user = User::where('email', $email)->where('activated', 1)->first();
            if($user)
            {
                if (\Hash::check($a, $user->secret_answer)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function sendPasswordReset($pwr)
    {
        \Mail::send('front.emails.auth.PasswordReset', array('token'=>$pwr->token), function($message) use ($pwr){
            $message->from('example@example.co.uk', 'Example');
            $message->to($pwr->email);
            $message->subject('Password Reset');
        });
    }

    private function authPasswordReset($email, $token)
    {
        $validator = \Validator::make(
            ['email' => $email, 'token' => $token],
            ['email' => 'required|email', 'token'=>'required|min:8']
        );

        if(!$validator->fails())
        {
            $pwr = PasswordReset::where('email',$email)->where('token', $token)->first();
            if($pwr)
            {
                $dt = new \DateTime();
                $df = \DateTime::createFromFormat('Y-m-d H:i:s', $pwr->created_at);
                $interval = $dt->diff($df);
                if(($interval->d == 0) && ($interval->m == 0) && ($interval->y==0))
                {
                    return true;
                }
            }
        }
        return ['Invalid password reset token. Please restart the process.'];
    }

    private function passwordChange($email, $password)
    {
        $user = User::where('email',$email)->where('activated',1)->first();
        $user->password = bcrypt($password);
        $user->save();
        return true;
    }

    public function loginRoute(Request $request)
    {
        $errors = $this->login($request);
        if($errors === true)
        {
            if($this->isAdmin())
            {
                return \Redirect::to('/admin');
            }
            else
            {
                return \Redirect::to('/dashboard');
            }
        }
        else
        {
            return \Redirect::to('/')->with('errors',$errors);
        }
    }


    public function passwordResetStartRoute()
    {
        if(!$this->isLoggedIn())
        {
            return \View::make('login.pages.password-reset');
        }
        else
        {
            if($this->isAdmin())
            {
                return \Redirect::to('/admin');
            }
            else
            {
                return \Redirect::to('/dashboard');
            }
        }
    }

    public function passwordResetFirstAuth()
    {
        if(!$this->isLoggedIn())
        {
            $input = \Input::all();
            $validator = \Validator::make(
                $input,
                [
                   'email'=>'required|email'
                ]
            );

            if(!$validator->fails())
            {
                $user = User::where('email', $input['email'])->first();
                if($user)
                {
                    $secret_question = $user->secret_question;
                    if($user->secret_question != null)
                    {
                        \View::share('secret_question', $secret_question);
                        \View::share('email', $user->email);
                        return \View::make('login.pages.password-reset-firstauth');
                    }
                    return \Redirect::back()->with('errors',['Unable to reset password. Please contact our administration team.']);
                }
                else
                {
                    return \Redirect::back()->with('errors',['Unable to reset password. Please contact our administration team.']);
                }
            }
            else
            {
                $errors = $validator->errors();
                return \Redirect::back()->with('errors',$errors->all());
            }
        }
        else
        {
            if($this->isAdmin())
            {
                return \Redirect::to('/admin');
            }
            else
            {
                return \Redirect::to('/dashboard');
            }
        }
    }

    public function passwordResetFirstAuthSubmit()
    {
        if(!$this->isLoggedIn())
        {
            $errors = $this->reqPasswordReset();
            if($errors === true)
            {
                return \View::make('login.pages.password-reset-first-auth-complete');
            }
            else
            {
                return \Redirect::to('/user/reset-password')->with('errors', $errors);
            }
        }
        else
        {
            if($this->isAdmin())
            {
                return \Redirect::to('/admin');
            }
            else
            {
                return \Redirect::to('/dashboard');
            }
        }
    }

    public function passwordResetSecondAuth($token)
    {
        \View::share('token',$token);
        return \View::make('login.pages.password-reset-second-auth-form');
    }

    public function passwordResetSecondAuthSubmit()
    {
        $errors = [''];
        $input = \Input::all();
        $validator = \Validator::make(
            $input,
            ['email'=>'required|email', 'reset_token'=>'required|min:8|max:8']
        );

        if(!$validator->fails())
        {
            $errors = $this->authPasswordReset($input['email'], $input['reset_token']);
            if($errors === true)
            {
                \View::share('token', $input['reset_token']);
                \View::share('email', $input['email']);
                return \View::make('login.pages.password-reset-form');
            }
        }
        else
        {
            $errors = $validator->errors();
            $errors = $errors->all();
        }
        return \Redirect::back()->with('errors', $errors);
    }

    public function passwordChangeFormSubmit()
    {
        $errors = [''];
        $input = \Input::all();
        $validator = \Validator::make(
            $input,
            ['email'=>'required|email', 'reset_token'=>'required|min:8|max:8', 'password'=> array('required','min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', 'confirmed')]
        );

        if(!$validator->fails())
        {
            $errors = $this->authPasswordReset($input['email'], $input['reset_token']);
            if($errors === true)
            {
                $this->passwordChange($input['email'], $input['password']);
                PasswordReset::deleteByEmail($input['email']);
                return \View::make('login.pages.password-changed');
            }
        }
        else
        {
            $errors = $validator->errors();
            $errors = $errors->all();
        }
        return \Redirect::to('/user/reset-password-2auth/'.$input['reset_token'])->with('errors', $errors)->with('email', $input['email'])->with('reset_token', $input['reset_token']);
    }

    public function logout()
    {
        if($this->isLoggedIn())
        {
            \Session::forget('user');
        }
        return \Redirect::to('/');
    }

    public function activateIndex($token)
    {
        \View::share('token', $token);
        return \View::make('login.pages.activate');
    }

    public function activate()
    {
        $input = \Input::all();

        $validator = \Validator::make(
            $input,
            [
                'email'=>['email','required'],
                'token'=>['required'],
                'password'=>array('required','min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', 'confirmed'),
                'secret_answer'=>['required', 'min:5', 'max:15', 'regex:/(^[A-Za-z0-9 ]+$)+/', 'confirmed'],
                'secret_question'=>['required', 'min:5', 'max:15', 'regex:/(^[A-Za-z0-9 ]+$)+/']
            ]
        );

        if(!$validator->fails())
        {
            $inv = UserInvite::where('email_address', $input['email'])->where('status', 'open')->first();
            if($inv)
            {
                if(\Hash::check($input['token'], $inv->token))
                {
                    $user = User::where('id', $inv->user_id)->where('activated',false)->first();
                    if($user)
                    {
                        $user->secret_question = $input['secret_question'];
                        $user->secret_answer = bcrypt($input['secret_answer']);
                        $user->password = bcrypt($input['password']);
                        $user->activated = true;

                        $inv->status = 'closed';
                        $user->save();
                        $inv->save();

                        return \View::make('login.pages.activated');
                    }
                }
            }
            return \Redirect::back()->with('errors',['error'=>'Could not find open user invite.']);
        }
        $errors = $validator->errors()->all();
        return \Redirect::back()->with('errors',$errors);
    }
}