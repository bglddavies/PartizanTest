@extends('login.layout._layout')
@section('title')
    Activate Account
@endsection
@section('body')
    <div class="full-page" style="background-image:url(/img/office-splash.jpg); background-position:center center;height:100%; width:100%; position:absolute;">
        <div class="container-div" style="background-color:rgba(0,0,0,0.6); height:100%; width:100%; position:absolute;">
            <div class="content-container" style="background-color: rgba(0,0,0,0.5); position: relative; margin-left: auto; margin-right: auto; min-width: 350px; width: 30%; margin-top: 20vh; height: 690px;">
                <h1 style="color:#fff; text-align: center; padding-top:20px;">Activate Account</h1>
                <p style="text-align: center; color:#fff; font-size:14px;">PASSWORD AT LEAST one uppercase letter, one lowercase letter, one number, one symbol and 6 characters long</p>
                <p style="text-align: center; color:#fff; font-size:14px;">SECRET QUESTION AND SECRET ANSWER between 5 and 15 characters. NUMBERS, LETTERS AND SPACE CHARACTERS ONLY</p>
                <form action="/user/activate/post" id="activate-form" method="post">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <input type="hidden" name="token" value="{{{$token}}}"/>
                    <input type="text" name="email" placeholder="Email Address">
                    <input type="password" name="password" placeholder="Password">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password">
                    <input type="text" name="secret_question" placeholder="Secret Question">
                    <input type="password" name="secret_answer" placeholder="Secret Answer">
                    <input type="password" name="secret_answer_confirmation" placeholder="Confirm Secret Answer">
                    <input type="Submit" class="btn btn-success" value="Activate">
                    <br>
                    @if(isset($errors))
                        @foreach($errors as $error)
                            <p class="error">
                                {{$error}}
                            </p>
                        @endforeach
                    @endif
                </form>
            </div>
        </div>
    </div>

    <style>
        #activate-form input[type="text"], #activate-form input[type="password"]{
            width:90%;
            margin-left:auto;
            margin-right:auto;
            border-radius:10px;
            border:none;
            display:block;
            padding:8px;
            margin-top:20px;
        }

        #activate-form input[type="submit"]{
            width:90%;
            margin-left:auto;
            margin-right:auto;
            margin-top:20px;
            display:block;
        }

        p.error{
            color:red;
            margin-bottom:2px !important;
            text-align:center;
        }
    </style>
@endsection