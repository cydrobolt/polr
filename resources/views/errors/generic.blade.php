@extends('layouts.base')

@section('content')
    <h1>{{ $status_code }}</h1>
    <p> {{ $status_message }}</p>
    <h4>Please contact an administrator to report this issue.</h4>
@endsection
