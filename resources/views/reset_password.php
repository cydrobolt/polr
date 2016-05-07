@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/reset_password.css' />
@endsection

@section('content')
<h1 class='header'>Reset Password</h1>

<div class='col-md-6 col-md-offset-3'>
    <form action method='POST'>
        <input type='password' placeholder='New Password' class='form-control password-input-pd'>
        <input type="hidden" name='_token' value='{{csrf_token()}}' />
        <input type='submit' value='Reset Password' class='form-control'>
    </form>
</div>
@endsection
