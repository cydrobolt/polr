@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/reset_password.css' />
@endsection

@section('content')
<h1 class='header'>{{ __('lost_password.step2.header') }}</h1>

<div class='col-md-6 col-md-offset-3'>
    <form action="#" method='POST'>
        <input type='password' id='passwordFirst' placeholder='{{ __('lost_password.step2.pass1') }}' class='form-control password-input-pd'>
        <input type='password' id='passwordConfirm' placeholder='{{ __('lost_password.step2.pass2') }}' class='form-control password-input-pd' name='new_password'>

        <input type="hidden" name='_token' value='{{csrf_token()}}' />
        <input type='submit' id='submitForm' value='{{ __('lost_password.step2.submit') }}' class='form-control'>
    </form>
</div>
@endsection

@section('js')
<script src='/js/reset_password.js'></script>
@endsection
