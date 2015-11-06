@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/about.css' />
@endsection

@section('content')
<img class='logo-img' src='/img/logo.png' />

<div class='about-contents'>
    @if ($role == "admin")
    <dl>
        <p>Build Information</p>
        <dt>Version: {{env('POLR_VERSION')}}</dt>
        <dt>Release date: {{env('POLR_RELDATE')}}</dt>
        <dt>App Install : {{env('APP_NAME')}} on {{env('APP_ADDRESS')}} on {{env('POLR_GENERATED_AT')}}<dt>
    </dl>
    <p>You are seeing the information above because you are logged in as an administrator.</p>
    @endif

    <p>{{env('APP_NAME')}} is powered by Polr 2, an open source, minimalist link shortening platform.
        Learn more at <a href='https://github.com/Cydrobolt/polr'>its Github page</a> or its <a href="//project.polr.me">project site</a>.
        <br />Polr is licensed under the GNU GPL License.
    </p>
</div>
<a href='#' class='btn btn-success license-btn'>More Information</a>
<div class="license" id="gpl-license">
    The GNU General Public License v3
    <br />
    Copyright (C) 2013-2015 Chaoyi Zha, the Polr Project
    <br />
    This program is free software: you can redistribute it and/or modify<br />
    it under the terms of the GNU General Public License as published by<br />
    the Free Software Foundation, either version 3 of the License, or<br />
    (at your option) any later version.<br />
    <br />
    This program is distributed in the hope that it will be useful,<br />
    but WITHOUT ANY WARRANTY; without even the implied warranty of<br />
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br />
    GNU General Public License for more details.<br />
    <br />
    You should have received a copy of the GNU General Public License<br />
    along with this program.  If not, see <a href='http://www.gnu.org/copyleft/gpl.html'>http://www.gnu.org/copyleft/gpl.html</a>.
</div>

@endsection

@section('js')
<script src='/js/about.js'></script>
@endsection
