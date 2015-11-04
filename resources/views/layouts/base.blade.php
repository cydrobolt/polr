<!--
Copyright (C) 2013-2015 Chaoyi Zha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

<!DOCTYPE html>
<html>
<head>
    <title>@section('title'){{env('APP_NAME')}}@show</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{env('APP_STYLESHEET')}}" />

    <link rel="stylesheet" href="/css/base.css" />
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" />
    <script src="/js/jquery-1.11.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    @yield('css')
</head>
<body>
    @include('snippets.navbar')
    <div class="container">
        <div class="content-div @if (!isset($no_div_padding)) content-div-padding @endif @if (isset($large)) jumbotron large-content-div @endif">
            @if (isset($error))
            <div class='alert'>
                {{$error}}
            </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    <script src='base.js'></script>
    @yield('js')
</body>
</html>
