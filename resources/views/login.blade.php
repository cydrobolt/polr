@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='css/login.css' />
@endsection

@section('content')
<div class="center-text">
<img loading="lazy" width="500" height="57" decoding="async" data-nimg="1" class="glq-home-logo" style="color:transparent" src="https://i.imgur.com/mXIdqe8.png" />
    <h1>Login</h1>Quickly shorten links for sharing - Integrated into <a target="_blank" href="https://ide.graphlinq.io">GraphLinq IDE</a><br/><br/>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="login" method="POST">
            <input type="text" placeholder="username" name="username" class="form-control login-field" />
            <input type="password" placeholder="password" name="password" class="form-control login-field" />
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
            <input type="submit" value="Login" class="login-submit btn btn-success btn-glq" />

            <p class='login-prompts'>
            @if (env('POLR_ALLOW_ACCT_CREATION') == true)
                <small>Don't have an account? <a href='{{route('signup')}}'>Register</a></small>
            @endif

            @if (env('SETTING_PASSWORD_RECOV') == true)
                <small>Forgot your password? <a href='{{route('lost_password')}}'>Reset</a></small>
            @endif
            </p>
            <p class='login-prompts'>
                <small>{{date('Y')}}&nbsp;<a target="_blank" href="https://graphlinq.io">GraphLinq</a> | <a target="_blank" href="https://app.graphlinq.io">GraphLinq App</a> | <a target="_blank" href="https://ide.graphlinq.io">GraphLinq IDE</a> | <a target="_blank" href="https://ai.graphlinq.io">GraphLinq AI</a> | <a target="_blank" href="https://analytics.graphlinq.io">GraphLinq Analytics</a></small>
            </p>
        </form>
    </div>
    <div class="col-md-3"></div>
</div
@endsection
