@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='css/login.css' />
@endsection

@section('content')
<div class="center-text">
    <h1>Login</h1><br/><br/>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="login" method="POST">
            <input type="text" placeholder="username" name="username" class="form-control login-field" />
            <input type="password" placeholder="password" name="password" class="form-control login-field" />
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
            <input type="submit" value="Login" class="login-submit btn btn-success" />

            <p class='login-prompts'>
            @if (env('POLR_ALLOW_ACCT_CREATION') == true)
                <small>Don't have an account? <a href='{{route('signup')}}'>Register</a></small>
            @endif

            @if (env('SETTING_PASSWORD_RECOV') == true)
                <small>Forgot your password? <a href='{{route('lost_password')}}'>Reset</a></small>
            @endif
            </p>
        </form>
    </div>
    <div class="col-md-3"></div>
</div
@endsection
