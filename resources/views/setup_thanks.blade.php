@extends('layouts.minimal')

@section('title')
Setup Completed
@endsection

@section('css')
<link rel='stylesheet' href='/css/default-bootstrap.min.css'>
<link rel='stylesheet' href='/css/setup.css'>
@endsection

@section('content')
<div class="navbar navbar-default navbar-fixed-top">
    <a class="navbar-brand" href="/">Polr</a>
</div>

<div class='row'>
    <div class='col-md-3'></div>

    <div class='col-md-6 setup-body well'>
        <div class='setup-center'>
            <img class='setup-logo' src='/img/logo.png'>
        </div>
        <h2>Setup Complete</h2>
        <p>Your Polr setup is complete. To continue, you may <a href='{{route('login')}}'>login</a> or
            access your <a href='{{route('index')}}'>home page</a>.
        </p>
        <p>Consider taking a look at the <a href='http://docs.polr.me/'>docs</a> or <a href='//github.com/cydrobolt/polr'>README</a>
            for assistance.
        </p>
        <p>You may also join us on IRC at <a href='//webchat.freenode.net/?channels=#polr'><code>#polr</code></a> on freenode for assistance or questions.</p>

        <p>Thanks for using Polr!</p>
    </div>

    <div class='col-md-3'></div>
</div>


@endsection
