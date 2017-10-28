@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/shorten_result.css' />
@endsection

@section('content')
<h3>Shortened URL</h3>
<div id='result-box' class='result-box' data-clipboard-target='.result-box'>{{$short_url}}</div>
<div class='shortened-control'>
    <a href='{{$short_url}}' target='_blank' class='btn btn-success'>Open in new tab</a>
    <a href='{{route('index')}}' class='btn btn-info'>Shorten another</a>
</div>
@endsection

@section('js')
<script src='/js/clipboard.min.js'></script>
<script src='/js/shorten_result.js'></script>
@endsection
