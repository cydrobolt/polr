@extends('layouts.base')

@section('content')
<h2>{{ __('errors.notice') }}</h2>
<h4>{{$message}}</h4>
@endsection
