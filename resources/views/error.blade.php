@extends('layouts.errors')

@section('content')
<h2>{{ __('errors.generic') }}</h2>
<h4>{{$message}}</h4>
@endsection
