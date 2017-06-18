@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/shorten_result.css' />
@endsection

@section('content')
<h3>Shortened URL</h3>
<input type='text' class='result-box form-control' value='{{$short_url}}' />
<div><img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{$short_url}}&choe=UTF-8"></div>
<a href='{{route('index')}}' class='btn btn-info back-btn'>Shorten another</a>
@endsection

@section('js')
<script src='/js/shorten_result.js'></script>
@endsection
