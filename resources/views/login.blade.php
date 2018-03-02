@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='css/login.css' />
@endsection

@section('content')
<div class="center-text">
    <h1>@lang('account.login.title')</h1><br/><br/>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="login" method="POST">
            <input type="text" placeholder="@lang('account.login.form.username')" name="username" class="form-control login-field" />
            <input type="password" placeholder="@lang('account.login.form.password')" name="password" class="form-control login-field" />
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
            <input type="submit" value="@lang('account.login.loginbtn')" class="login-submit btn btn-success" />

            <p class='login-prompts'>
            @if (env('POLR_ALLOW_ACCT_CREATION') == true)
                <small>@lang('account.register.question') <a href='{{route('signup')}}'>@lang('account.register.register')</a></small>
            @endif

            @if (env('SETTING_PASSWORD_RECOV') == true)
                <small>@lang('account.forgot.question') <a href='{{route('lost_password')}}'>@lang('account.forgot.reset')</a></small>
            @endif
            </p>
        </form>
    </div>
    <div class="col-md-3"></div>
</div
@endsection
