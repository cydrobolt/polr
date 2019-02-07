@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/lost_password.css' />
@endsection

@section('content')
<h1 class='header'>{{ __('lost_password.step1.header') }}</h1>

<div class='col-md-6 col-md-offset-3'>
    <form action='/lost_password' method='POST'>
        <input type='email' name='email' placeholder='{{ __('lost_password.step1.email') }}' class='form-control email-input-pd'>
        <input type="hidden" name='_token' value='{{csrf_token()}}' />
        <input type='submit' value='{{ __('lost_password.step1.submit') }}' class='form-control'>
    </form>
</div>
@endsection
