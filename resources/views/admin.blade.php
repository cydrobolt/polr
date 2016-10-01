@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/admin.css' />
@endsection

@section('content')
<div ng-controller="AdminCtrl" class="ng-root">
    <div class='col-md-2'>
        <ul class='nav nav-pills nav-stacked admin-nav' role='tablist'>
            <li role='presentation' aria-controls="home" class='admin-nav-item active'><a href='#home'>Home</a></li>
            <li role='presentation' aria-controls="links" class='admin-nav-item'><a href='#links'>Links</a></li>
            <li role='presentation' aria-controls="settings" class='admin-nav-item'><a href='#settings'>Settings</a></li>

            @if ($role == 'admin')
            <li role='presentation' class='admin-nav-item'><a href='#admin'>Admin</a></li>
            @endif

            @if ($api_active == 1)
            <li role='presentation' class='admin-nav-item'><a href='#developer'>Developer</a></li>
            @endif
        </ul>
    </div>
    <div class='col-md-10'>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <h2>Welcome to your {{env('APP_NAME')}} dashboard!</h2>
                <p>Use the links on the left hand side to navigate your {{env('APP_NAME')}} dashboard.</p>
            </div>

            <div role="tabpanel" class="tab-pane" id="links">
                @include('snippets.link_table', [
                    'links' => $user_links
                ])

                {!! $user_links->fragment('links')->render() !!}
                {{-- Add search functions --}}
            </div>

            <div role="tabpanel" class="tab-pane" id="settings">
                <h3>Change Password</h3>
                <form action='/admin/action/change_password' method='POST'>
                    Old Password: <input class="form-control password-box" type='password' name='current_password' />
                    New Password: <input class="form-control password-box" type='password' name='new_password' />
                    <input type="hidden" name='_token' value='{{csrf_token()}}' />
                    <input type='submit' class='btn btn-success change-password-btn'/>
                </form>
            </div>

            @if ($role == $admin_role)
            <div role="tabpanel" class="tab-pane" id="admin">
                <h3>Links</h3>

                @include('snippets.link_table', [
                    'links' => $admin_links
                ])

                {!! $admin_links->fragment('admin')->render() !!}

                <h3>Users</h3>
                @include('snippets.user_table', [
                    'users' => $admin_users,
                    'roles' => $user_roles
                ])

                {!! $admin_users->fragment('admin')->render() !!}

            </div>
            @endif

            @if ($api_active == 1)
            <div role="tabpanel" class="tab-pane" id="developer">
                <h3>Developer</h3>

                <p>API keys and documentation for developers.</p>
                <p>
                    Documentation:
                    <a href='http://docs.polr.me/en/latest/developer-guide/api/'>http://docs.polr.me/en/latest/developer-guide/api/</a>
                </p>

                <h4>API Key: </h4>
                <div class='row'>
                    <div class='col-md-8'>
                        <input class='form-control status-display' disabled type='text' value='{{$api_key}}'>
                    </div>
                    <div class='col-md-4'>
                        <a href='#' ng-click="generateNewAPIKey($event, '{{$user_id}}', true)" id='api-reset-key' class='btn btn-danger'>Reset</a>
                    </div>
                </div>


                <h4>API Quota: </h4>
                <h2 class='api-quota'>
                    @if ($api_quota == -1)
                        unlimited
                    @else
                        <code>{{$api_quota}}</code>
                    @endif
                </h2>
                <span> requests per minute</span>
            </div>
            @endif
        </div>
    </div>
</div>


@endsection

@section('js')
{{-- Include modal templates --}}
@include('snippets.modals')

{{-- Include extra JS --}}
<script src='/js/handlebars-v4.0.5.min.js'></script>
<script src='/js/api.js'></script>
<script src='/js/AdminCtrl.js'></script>

{{-- Extra templating --}}
<script id="api-modal-template" type="text/x-handlebars-template">
    <div>
        <p>
            <span>API Active</span>:

            <code class='status-display'>
                @{{#if api_active}}True@{{else}}False@{{/if}}</code>

            <a ng-click="toggleAPIStatus($event, '@{{user_id}}')" class='btn btn-xs btn-success'>toggle</a>
        </p>
        <p>
            <span>API Key: </span><code class='status-display'>@{{api_key}}</code>
            <a ng-click="generateNewAPIKey($event, '@{{user_id}}', false)" class='btn btn-xs btn-danger'>reset</a>
        </p>
        <p>
            <span>API Quota (req/min, -1 for unlimited):</span> <input type='number' class='form-control api-quota' value='@{{api_quota}}'>
            <a ng-click="updateAPIQuota($event, '@{{user_id}}')" class='btn btn-xs btn-warning'>change</a>
        </p>
    </div>
</script>

@endsection
