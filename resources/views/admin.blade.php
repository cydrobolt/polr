@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/admin.css' />
@endsection

@section('content')
<div class='col-md-3'>
    <ul class='nav nav-pills nav-stacked admin-nav' role='tablist'>
        <li role='presentation' aria-controls="home" class='admin-nav-item active'><a href='#home'>Home</a></li>
        <li role='presentation' aria-controls="links" class='admin-nav-item'><a href='#links'>Links</a></li>
        <li role='presentation' aria-controls="settings" class='admin-nav-item'><a href='#settings'>Settings</a></li>

        @if ($role == 'admin')
        <li role='presentation' class='admin-nav-item'><a href='#'>Admin</a></li>
        @endif
    </ul>
</div>
<div class='col-md-9'>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <h2>Welcome to your {{env('APP_NAME')}} dashboard!</h2>
            <p>Use the links on the left hand side to navigate your {{env('APP_NAME')}} dashboard.</p>
        </div>

        <div role="tabpanel" class="tab-pane" id="links">

        </div>

        <div role="tabpanel" class="tab-pane" id="settings">

        </div>
    </div>
</div>


@endsection

@section('js')
<script src='/js/admin.js'></script>
@endsection
