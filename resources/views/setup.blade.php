@extends('layouts.minimal')

@section('title')
Setup
@endsection

@section('css')
<link rel='stylesheet' href='/css/default-bootstrap.min.css'>
<link rel='stylesheet' href='/css/setup.css'>
@endsection

@section('content')
<div class="navbar navbar-default navbar-fixed-top">
    <a class="navbar-brand" href="/">Polr</a>
</div>

<div class='row'>
    <div class='col-md-3'></div>

    <div class='col-md-6 setup-body well'>
        <div class='setup-center'>
            <img class='setup-logo' src='/img/logo.png'>
        </div>

        <form class='setup-form' method='POST' action='/setup'>
            <h4>Database Configuration</h4>

            <p>Database Host:</p>
            <input type='text' class='form-control' name='db:host' value='localhost'>

            <p>Database Port:</p>
            <input type='text' class='form-control' name='db:port' value='3306'>

            <p>Database Username:</p>
            <input type='text' class='form-control' name='db:username' value='root'>

            <p>Database Password:</p>
            <input type='password' class='form-control' name='db:password' value='password'>

            <p>Database Name:</p>
            <input type='text' class='form-control' name='db:name' value='polr'>


            <h4>Application Settings</h4>

            <p>Application Name:</p>
            <input type='text' class='form-control' name='app:name' value='Polr'>

            <p>Application protocol:</p>
            <input type='text' class='form-control' name='app:protocol' value='http://'>

            <p>Application URL (path to Polr, no http://, or trailing slash):</p>
            <input type='text' class='form-control' name='app:external_url' value='yoursite.com'>

            <p>Shortening Permissions:</p>
            <select name='setting:shorten_permission' class='form-control'>
                <option value='false' selected='selected'>All users can shorten URLs</option>
                <option value='true'>Only logged in users may shorten URLs</option>
            </select>

            <p>Show Public Interface:</p>
            <select name='setting:public_interface' class='form-control'>
                <option value='true' selected='selected'>Show public interface (default)</option>
                <option value='false'>Hide public interface (for private shorteners)</option>
            </select>

            <p>If public interface is hidden, redirect index page to:</p>
            <input type='text' class='form-control' name='setting:index_redirect' placeholder='http://your-main-site.com'>
            <p class='text-muted'>
                If a redirect is enabled, you will need to go to
                http://yoursite.com/login before you can access the index
                page.
            </p>

            <p>URL Ending Base:</p>
            <select name='setting:base' class='form-control'>
                <option value='32' selected='selected'>32 -- lowercase letters & numbers (default)</option>
                <option value='62'>62 -- lowercase, uppercase letters & numbers</option>
            </select>

            <h4>Admin Account Settings</h4>

            <p>Admin Username:</p>
            <input type='text' class='form-control' name='acct:username' value='polr'>

            <p>Admin Email:</p>
            <input type='text' class='form-control' name='acct:email' value='polr@admin.tld'>

            <p>Admin Password:</p>
            <input type='password' class='form-control' name='acct:password' value='polr'>

            <h4>SMTP Settings</h4>
            <p class='text-muted'>(leave blank if you are not using email verification/password recovery)</p>

            <p>SMTP Server:</p>
            <input type='text' class='form-control' name='app:smtp_server' placeholder='smtp.gmail.com'>

            <p>SMTP Port:</p>
            <input type='text' class='form-control' name='app:smtp_port' placeholder='25'>

            <p>SMTP Username:</p>
            <input type='text' class='form-control' name='app:smtp_username' placeholder='example@gmail.com'>

            <p>SMTP Password:</p>
            <input type='password' class='form-control' name='app:smtp_password' placeholder='password'>

            <p>SMTP From:</p>
            <input type='text' class='form-control' name='app:smtp_from' placeholder='example@gmail.com'>
            <p>SMTP From Name:</p>
            <input type='text' class='form-control' name='app:smtp_from_name' placeholder='noreply'>

            <h4>API Settings</h4>

            <p>Anonymous API:</p>
            <select name='setting:anon_api' class='form-control'>
                <option selected value='false'>Off -- only registered users can use API</option>
                <option value='true'>On -- empty key API requests are allowed</option>
            </select>

            <p>Automatic API Assignment:</p>
            <select name='setting:auto_api_key' class='form-control'>
                <option selected value='false'>Off -- admins must manually enable API for each user</option>
                <option value='true'>On -- each user receives an API key</option>
            </select>

            <h4>Other Settings</h4>

            <p>Registration:</p>
            <select name='setting:registration_permission' class='form-control'>
                <option value='none'>No registration</option>
                <option value='email'>Email verification required</option>
                <option value='no-verification'>No email verification required</option>
            </select>

            <p>Password Recovery:</p>
            <select name='setting:password_recovery' class='form-control'>
                <option value='false'>No (default)</option>
                <option value='true'>Yes</option>
            </select>
            <p class='text-muted'>
                Please ensure SMTP is properly set up before enabling password recovery.
            </p>

            {{-- <p>Path relative to root (leave blank if /, if http://site.com/polr, then write /polr/):</p>
            <input type='text' class='form-control' name='path' placeholder='/polr/' value=''> --}}

            <p>Theme (click <a href='https://github.com/cydrobolt/polr/wiki/Themes-Screenshots'>here</a> for screenshots:</p>
            <select name='app:stylesheet' class='form-control'>
                <option value=''>Modern (default)</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/cyborg/bootstrap.min.css'>Midnight Black</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/united/bootstrap.min.css'>Orange</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/simplex/bootstrap.min.css'>Crisp White</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/darkly/bootstrap.min.css'>Cloudy Night</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/cerulean/bootstrap.min.css'>Calm Skies</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/paper/bootstrap.min.css'>Android Material Design</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/superhero/bootstrap.min.css'>Blue Metro</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/sandstone/bootstrap.min.css'>Sandstone</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/cyborg/bootstrap.min.css'>Jet Black</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/lumen/bootstrap.min.css'>Newspaper</option>
            </select>

            <div class='setup-form-buttons'>
                <input type='submit' class='btn btn-success' value='Install'>
                <input type='reset' class='btn btn-warning' value='Clear Fields'>
            </div>
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
        </form>
    </div>

    <div class='col-md-3'></div>
</div>

<div class='setup-footer well'>
    Polr is <a href='http://en.wikipedia.org/wiki/Open-source_software'>open-source
    software</a> licensed under the <a href='//www.gnu.org/copyleft/gpl.html'>GPLv2+
    License</a>. By continuing to use Polr, you agree to the terms of the GPL License.

    <div>
        Polr Version {{env('VERSION')}} released {{env('VERSION_RELMONTH')}} {{env('VERSION_RELDAY')}}, {{env('VERSION_RELYEAR')}} -
        <a href='//github.com/cydrobolt/polr'>Github</a>

        <div class='footer-well'>
            &copy; Copyright {{env('VERSION_RELYEAR')}}
            <a class='footer-link' href='//cydrobolt.com'>Chaoyi Zha</a> &
            <a class='footer-link' href='//github.com/Cydrobolt/polr/graphs/contributors'>Other Polr Contributors</a>
        </div>
    </div>
</div>

@endsection
