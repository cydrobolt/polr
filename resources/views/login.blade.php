@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='css/login.css' />
@endsection

@section('content')
<div class="center-text">
    <h1>{{ __('account.login.title') }}</h1><br/><br/>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="login" method="POST">
            <input type="text" placeholder="{{ __('account.login.form.username') }}" name="username" class="form-control login-field" />
            <input type="password" placeholder="{{ __('account.login.form.password') }}" name="password" class="form-control login-field" />
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
            <input type="submit" value="{{ __('account.login.loginbtn') }}" class="login-submit btn btn-success" />

            <p class='login-prompts'>
            @if (env('POLR_ALLOW_ACCT_CREATION') == true)
                <small>{{ __('account.register.question') <a href='{{route('signup')}}'>{{ __('account.register.register') }} }}</a></small>
            @endif

            @if (env('SETTING_PASSWORD_RECOV') == true)
                <small>{{ __('account.forgot.question') <a href='{{route('lost_password')}}'>{{ __('account.forgot.reset') }} }}</a></small>
            @endif
            </p>
        </form>
    </div>
    <div class="col-md-3"></div>
</div
@endsection
