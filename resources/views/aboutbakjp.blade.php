@extends('layouts.base')

@section('css')
    <link rel='stylesheet' href='/css/aboutbakjp.css' />
@endsection

@section('content')
    <div class="logotxt">
        bak.jp URL shortener service
    </div>

    <div class='about-contents'>
        <p>{{env('APP_NAME')}} is serviced by <a href="https://tailshape.jp/" target="_new">Tailshape Inc.</a></p>
        <p>{{env('APP_NAME')}} is powered by <a href="{{ route('about') }}">Polr 2</a>, an open source, minimalist link shortening platform.</p>
    </div>
@endsection

