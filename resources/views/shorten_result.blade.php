@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/shorten_result.css' />
@endsection

@section('content')
<h3>Shortened URL</h3>
<input type='text' class='result-box form-control' value='{{$short_url}}' />
<a id="generate-qr-code" class='btn btn-primary'>Generate QR Code</a>
<a href='{{route('index')}}' class='btn btn-info'>Shorten another</a>

<div class="qr-code-container"></div>

@endsection


@section('js')
<script src='/js/qrcode.min.js'></script>
<script src='/js/shorten_result.js'></script>
@endsection
