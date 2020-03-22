@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/signup.css' />
@endsection

@section('content')
<div class='col-md-6'>
    <h2 class='title'>{{__('Register')}}</h2>

    <form action='/signup' method='POST'>
        {{__('Username')}}: <input type='text' name='username' class='form-control form-field' placeholder="{{__('Username')}}" />
        {{__('Password')}}: <input type='password' name='password' class='form-control form-field' placeholder="{{__('Password')}}" />
        {{__('Email')}}: <input type='email' name='email' class='form-control form-field' placeholder="{{__('Email')}}"  />

        @if (env('POLR_ACCT_CREATION_RECAPTCHA'))
        <div class="g-recaptcha" data-sitekey="{{env('POLR_RECAPTCHA_SITE_KEY')}}"></div>
        @endif

        <input type="hidden" name='_token' value='{{csrf_token()}}' />
        <input type="submit" class="btn btn-default btn-success" value="{{__('Regist')}}"/>
        <p class='login-prompt'>
            <small>{{__('Already have an account?')}} <a href='{{route('login')}}'>{{__('Login')}}</a></small>
        </p>
    </form>
</div>
<div class='col-md-6 hidden-xs hidden-sm'>
    <div class='right-col-one'>
        <h4>{{__('Username')}}</h4>
        <p>{{__('The username you will use to login.')}}</p>
    </p>
    <div class='right-col-next'>
        <div class='right-col'>
            <h4>{{__('Password')}}</h4>
            <p>{{__('The secure password you will use to login.')}}</p>
        </p>
    </div>
    <div class='right-col-next'>
        <h4>{{__('Email')}}</h4>
        <p>{{__('The email you will use to verify your account or to recover your account.')}}</p>
    </p>
</div>
@endsection

@section('js')
    @if (env('POLR_ACCT_CREATION_RECAPTCHA'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
