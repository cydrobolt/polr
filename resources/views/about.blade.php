@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/about.css' />
<link rel='stylesheet' href='/css/effects.css' />
@endsection

@section('content')
<div class='well logo-well'>
    <img class='logo-img' src='/img/logo.png' />
</div>

<div class='about-contents'>
    @if ($role == "admin")
    <dl>
        <p>@lang('about.buildinfo.title')</p>
        <dt>@lang('about.buildinfo.version', ['ver' => env('POLR_VERSION')])</dt>
        <dt>@lang('about.buildinfo.release', ['reldate' => env('POLR_RELDATE')])</dt>
        <dt>@lang('about.buildinfo.appinstall', ['appname' => env('APP_NAME'), 'appaddress' => env('APP_ADDRESS'), 'genat' => env('POLR_GENERATED_AT')])<dt>
    </dl>
    <p>@lang('about.buildinfo.admin')</p>
    @endif

    <p>@lang('about.about.powered', ['app' => env('APP_NAME')])
        @lang('about.about.learnmore')
        <br />@lang('about.about.license')
    </p>
</div>
<a href='#' class='btn btn-success license-btn'>@lang('about.moreinfo')</a>
<pre class="license" id="gpl-license">
Copyright (C) 2013-2017 Chaoyi Zha

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
</pre>

@endsection

@section('js')
<script src='/js/about.js'></script>
@endsection
