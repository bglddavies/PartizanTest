@extends('login.layout._layout')
@section('title')
    Password Reset
@endsection
@section('body')
    <div class="full-page" style="background-image:url(/img/office-splash.jpg); background-position:center center;height:100%; width:100%; position:absolute;">
        <div class="container-div" style="background-color:rgba(0,0,0,0.6); height:100%; width:100%; position:absolute;">
            <div class="content-container" style="background-color: rgba(0,0,0,0.5); position: relative; margin-left: auto; margin-right: auto; min-width: 350px; width: 30%; margin-top: 30vh; height: 200px;">
                <h1 style="color:#fff; text-align: center; padding-top:20px;">Success</h1>
                <p style="text-align: center; color:#fff; font-size:20px;">We have emailed you a password reset link. Please follow the link before 12am tomorrow.</p>
            </div>
        </div>
    </div>
@endsection