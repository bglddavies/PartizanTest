@extends('login.layout._layout')
@section('title')
    Password Reset
@endsection
@section('body')
    <div class="full-page" style="background-image:url(/img/office-splash.jpg); background-position:center center;height:100%; width:100%; position:absolute;">
        <div class="container-div" style="background-color:rgba(0,0,0,0.6); height:100%; width:100%; position:absolute;">
            <div class="content-container" style="background-color: rgba(0,0,0,0.5); position: relative; margin-left: auto; margin-right: auto; min-width: 350px; width: 30%; margin-top: 30vh; height: 300px;">
                <h1 style="color:#fff; text-align: center; padding-top:20px;">Reset Password</h1>
                <form action="/user/request-reset" id="password-reset-form" method="post">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <input type="text" name="email" placeholder="Email Address">
                    <input type="Submit" class="btn btn-success" value="Continue">
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
        #password-reset-form input[type="text"], #login-form input[type="password"]{
            width:90%;
            margin-left:auto;
            margin-right:auto;
            border-radius:10px;
            border:none;
            display:block;
            padding:8px;
            margin-top:20px;
        }

        #password-reset-form input[type="submit"]{
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