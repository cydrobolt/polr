@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/signup.css' />
@endsection

@section('content')
<div class='col-md-6'>
    <h2 class='title'>@lang('account.register.title')</h2>

    <form action='/signup' method='POST'>
        @lang('account.register.form.username.label') <input type='text' name='username' class='form-control form-field' placeholder='@lang('account.register.form.username.placeholder')' />
        @lang('account.register.form.password.label') <input type='password' name='password' class='form-control form-field' placeholder='@lang('account.register.form.password.placeholder')' />
        @lang('account.register.form.email.label') <input type='email' name='email' class='form-control form-field' placeholder='@lang('account.register.form.email.placeholder')' />

        @if (env('POLR_ACCT_CREATION_RECAPTCHA'))
        <div class="g-recaptcha" data-sitekey="{{env('POLR_RECAPTCHA_SITE_KEY')}}"></div>
        @endif

        <input type="hidden" name='_token' value='{{csrf_token()}}' />
        <input type="submit" class="btn btn-default btn-success" value="@lang('account.register.registerbtn')"/>
        <p class='login-prompt'>
            <small>@lang('account.login.question') <a href='{{route('login')}}'>@lang('account.login.login')</a></small>
        </p>
    </form>
</div>
<div class='col-md-6 hidden-xs hidden-sm'>
    <div class='right-col-one'>
        <h4>@lang('account.register.form.username.placeholder')</h4>
        <p>@lang('account.register.form.username.help', ['app' => env('APP_NAME')])</p>
    </p>
    <div class='right-col-next'>
        <div class='right-col'>
            <h4>@lang('account.register.form.password.placeholder')</h4>
            <p>@lang('account.register.form.password.help', ['app' => env('APP_NAME')])</p>
        </p>
    </div>
    <div class='right-col-next'>
        <h4>@lang('account.register.form.email.placeholder')</h4>
        <p>@lang('account.register.form.email.help')</p>
    </p>
</div>
@endsection

@section('js')
    @if (env('POLR_ACCT_CREATION_RECAPTCHA'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
