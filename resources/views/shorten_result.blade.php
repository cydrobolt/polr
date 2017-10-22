@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/shorten_result.css' />
@endsection

@section('content')
<h3>Shortened URL</h3>
<input type='text' class='result-box form-control' value='{{$short_url}}' />
<button id="generateQRCode" class='btn btn-primary back-btn'>Generate QR Code</button>
<a href='{{route('index')}}' class='btn btn-info back-btn'>Shorten another</a>

<div class="qrCodeContainer"></div>

@endsection


@section('js')
<script src='/js/qrcode.min.js'></script>
<script src='/js/shorten_result.js'></script>
@endsection
