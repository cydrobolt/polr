@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='css/login.css' />
@endsection

@section('content')
<div class="center-text">
    <h1>{{__('Login')}}</h1><br/><br/>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="login" method="POST">
            <input type="text" placeholder="{{__('Username')}}" name="username" class="form-control login-field" />
            <input type="password" placeholder="{{__('Password')}}" name="password" class="form-control login-field" />
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
            <input type="submit" value={{__('Login')}} class="login-submit btn btn-success" />

            <p class='login-prompts'>
            @if (env('POLR_ALLOW_ACCT_CREATION') == true)
                <small>{{__("Don't have an account?")}} <a href='{{route('signup')}}'>{{__('Register')}}</a></small>
            @endif

            @if (env('SETTING_PASSWORD_RECOV') == true)
                <small>{{__('Forgot your password?')}} <a href='{{route('lost_password')}}'>{{__('Reset')}}</a></small>
            @endif
            </p>
        </form>
    </div>
    <div class="col-md-3"></div>
</div
@endsection
