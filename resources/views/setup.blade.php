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

<div class="row" ng-controller="SetupCtrl" class="ng-root">
    <div class='col-md-3'></div>

    <div class='col-md-6 setup-body well'>
        <div class='setup-center'>
            <img class='setup-logo' src='/img/logo.png'>
        </div>

        <form class='setup-form' method='POST' action='/setup'>
            <h4>{{ __('setup.database') }}</h4>

            <p>{{ __('setup.database.host') }}</p>
            <input type='text' class='form-control' name='db:host' value='localhost'>

            <p>{{ __('setup.database.port') }}</p>
            <input type='text' class='form-control' name='db:port' value='3306'>

            <p>{{ __('setup.database.username') }}</p>
            <input type='text' class='form-control' name='db:username' value='root'>

            <p>{{ __('setup.database.password') }}</p>
            <input type='password' class='form-control' name='db:password' value='password'>

            <p>
                {{ __('setup.database.name') }}
                <setup-tooltip content="{{ __('setup.database.nametooltip') }}"></setup-tooltip>
            </p>
            <input type='text' class='form-control' name='db:name' value='polr'>


            <h4>{{ __('setup.app') }}</h4>

            <p>{{ __('setup.app.name') }}</p>
            <input type='text' class='form-control' name='app:name' value='Polr'>

            <p>{{ __('setup.app.protocol') }}</p>
            <input type='text' class='form-control' name='app:protocol' value='http://'>

            <p>{{ __('setup.app.url') }}</p>
            <input type='text' class='form-control' name='app:external_url' value='yoursite.com'>

            <p>
                {{ __('setup.advanalytics') }}
                <button data-content="{{ __('setup.app.advanalytics.tooltip') }}"
                    type="button" class="btn btn-xs btn-default setup-qmark" data-toggle="popover">?</button>
            </p>
            <select name='setting:adv_analytics' class='form-control'>
                <option value='false' selected='selected'>{{ __('setup.app.advanalytics.disable') }}</option>
                <option value='true'>{{ __('setup.app.advanalytics.enable') }}</option>
            </select>

            <p>{{ __('setup.app.perm') }}</p>
            <select name='setting:shorten_permission' class='form-control'>
                <option value='false' selected='selected'>{{ __('setup.app.perm.anyone') }}</option>
                <option value='true'>{{ __('setup.app.perm.logged') }}</option>
            </select>

            <p>{{ __('setup.app.public') }}</p>
            <select name='setting:public_interface' class='form-control'>
                <option value='true' selected='selected'>{{ __('setup.app.public.enable') }}</option>
                <option value='false'>{{ __('setup.app.public.disable') }}</option>
            </select>

            <p>{{ __('setup.app.disabled') }}</p>
            <select name='setting:redirect_404' class='form-control'>
                <option value='false' selected='selected'>{{ __('setup.app.disabled.err') }}</option>
                <option value='true'>{{ __('setup.app.disabled.redir') }}</option>
            </select>

            <p>
                {{ __('setup.app.redirurl') }}
                <setup-tooltip content="{{ __('setup.app.redirurl.tooltip') }}"></setup-tooltip>
            </p>
            <input type='text' class='form-control' name='setting:index_redirect' placeholder='http://your-main-site.com'>
            <p class='text-muted'>
                {{ __('setup.app.redirurl.tooltip2') }}
            </p>

            <p>
                {{ __('setup.app.urlendingtype') }}
                <setup-tooltip content="{{ __('setup.app.urlendingtype.tooltip') }}"></setup-tooltip>
            </p>
            <select name='setting:pseudor_ending' class='form-control'>
                <option value='false' selected='selected'>{{ __('setup.app.urlendingtype.base') }}</option>
                <option value='true'>{{ __('setup.app.urlendingtype.strings') }}</option>
            </select>

            <p>
                {{ __('setup.app.urlendingbase') }}
                <setup-tooltip content="{{ __('setup.app.urlendingbase.tooltip') }}"></setup-tooltip>
            </p>
            <select name='setting:base' class='form-control'>
                <option value='32' selected='selected'>{{ __('setup.app.urlendingbase.32') }}</option>
                <option value='62'>{{ __('setup.app.urlendingbase.62') }}</option>
            </select>

            <h4>
                {{ __('setup.admin') }}
                <setup-tooltip content="{{ __('setup.admin.tooltip') }}"></setup-tooltip>
            </h4>

            <p>{{ __('setup.admin.username') }}</p>
            <input type='text' class='form-control' name='acct:username' value='polr'>

            <p>{{ __('setup.admin.email') }}</p>
            <input type='text' class='form-control' name='acct:email' value='polr@admin.tld'>

            <p>{{ __('setup.admin.password') }}</p>
            <input type='password' class='form-control' name='acct:password' value='polr'>

            <h4>
                {{ __('setup.smtp') }}
                <setup-tooltip content="{{ __('setup.smtp.tooltip') }}"></setup-tooltip>
            </h4>

            <p>{{ __('setup.smtp.server') }}</p>
            <input type='text' class='form-control' name='app:smtp_server' placeholder='smtp.gmail.com'>

            <p>{{ __('setup.smtp.port') }}</p>
            <input type='text' class='form-control' name='app:smtp_port' placeholder='25'>

            <p>{{ __('setup.smtp.username') }}</p>
            <input type='text' class='form-control' name='app:smtp_username' placeholder='example@gmail.com'>

            <p>{{ __('setup.smtp.password') }}</p>
            <input type='password' class='form-control' name='app:smtp_password' placeholder='password'>

            <p>{{ __('setup.smtp.from') }}</p>
            <input type='text' class='form-control' name='app:smtp_from' placeholder='example@gmail.com'>
            <p>{{ __('setup.smtp.fromname') }}</p>
            <input type='text' class='form-control' name='app:smtp_from_name' placeholder='noreply'>

            <h4>{{ __('setup.api') }}</h4>

            <p>{{ __('setup.api.anonymous') }}</p>
            <select name='setting:anon_api' class='form-control'>
                <option selected value='false'>{{ __('setup.api.anonymous.off') }}</option>
                <option value='true'>{{ __('setup.api.anonymous.on') }}</option>
            </select>

            <p>
                {{ __('setup.api.quota') }}
                <setup-tooltip content="{{ __('setup.api.quota.tooltip') }}"></setup-tooltip>
            </p>
            <input type='text' class='form-control' name='setting:anon_api_quota' placeholder='10'>

            <p>{{ __('setup.api.autoapi') }}</p>
            <select name='setting:auto_api_key' class='form-control'>
                <option selected value='false'>{{ __('setup.api.autoapi.off') }}</option>
                <option value='true'>{{ __('setup.api.autoapi.on') }}</option>
            </select>

            <h4>{{ __('setup.other') }}</h4>

            <p>
                {{ __('setup.other.register') }}
                <setup-tooltip content="{{ __('setup.other.register') }}"></setup-tooltip>
            </p>
            <select name='setting:registration_permission' class='form-control'>
                <option value='none'>{{ __('setup.other.register.off') }}</option>
                <option value='email'>{{ __('setup.other.register.email') }}</option>
                <option value='no-verification'>{{ __('setup.other.register.on') }}</option>
            </select>

            <p>
                {{ __('setup.other.restrictemail') }}
                <setup-tooltip content="{{ __('setup.other.restrictemail.tooltip') }}"></setup-tooltip>
            </p>
            <select name='setting:restrict_email_domain' class='form-control'>
                <option value='false'>{{ __('setup.other.restrictemail.any') }}</option>
                <option value='true'>{{ __('setup.other.restrictemail.restrict') }}</option>
            </select>

            <p>
                {{ __('setup.other.restrictemail.emaillist') }}
                <setup-tooltip content="{{ __('setup.other.restrictemail.listtooltip') }}"></setup-tooltip>
            </p>
            <input type='text' class='form-control' name='setting:allowed_email_domains' placeholder='company.com,company-corp.com'>

            <p>
                {{ __('setup.other.passwordrecover') }}
                <setup-tooltip content="{{ __('setup.other.passwordrecover.tooltip') }}"></setup-tooltip>
            </p>
            <select name='setting:password_recovery' class='form-control'>
                <option value='false'>{{ __('setup.other.passwordrecover.disabled') }}</option>
                <option value='true'>{{ __('setup.other.passwordrecover.enabled') }}</option>
            </select>
            <p class='text-muted'>
                {{ __('setup.other.passwordrecover.tooltip2') }}
            </p>

            <p>
                {{ __('setup.other.recaptcha') }}
                <setup-tooltip content="{{ __('setup.other.recaptcha.tooltip') }}"></setup-tooltip>
            </p>
            <select name='setting:acct_registration_recaptcha' class='form-control'>
                <option value='false'>{{ __('setup.other.recaptcha.notrequire') }}</option>
                <option value='true'>{{ __('setup.other.recaptcha.require') }}</option>
            </select>

            <p>
                {{ __('setup.other.recaptcha.config') }}
                <setup-tooltip content="{{ __('setup.other.recaptcha.config.tooltip') }}"></setup-tooltip>
            </p>

            <p>
                {{ __('setup.other.recaptcha.config.site') }}
            </p>
            <input type='text' class='form-control' name='setting:recaptcha_site_key'>

            <p>
                {{ __('setup.other.recaptcha.config.secret') }}
            </p>
            <input type='text' class='form-control' name='setting:recaptcha_secret_key'>

            <p class='text-muted'>
                You can obtain reCAPTCHA keys from <a href="https://www.google.com/recaptcha/admin">Google's reCAPTCHA website</a>.
                {{ __('setup.other.recaptcha.config.text', ['link' => '<a href="https://www.google.com/recaptcha/admin">' . __('setup.other.recaptcha.config.link') . '</a>']) }}
            </p>

            <p>{{ __('setup.other.theme', ['link' => '<a href="https://github.com/cydrobolt/polr/wiki/Themes-Screenshots">' . __('setup.other.theme.link') . '</a>']) }}</p>
            <select name='app:stylesheet' class='form-control'>
                <option value=''>Modern (default)</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css'>Midnight Black</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/united/bootstrap.min.css'>Orange</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/simplex/bootstrap.min.css'>Crisp White</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/darkly/bootstrap.min.css'>Cloudy Night</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css'>Calm Skies</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/paper/bootstrap.min.css'>Google Material Design</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/superhero/bootstrap.min.css'>Blue Metro</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/sandstone/bootstrap.min.css'>Sandstone</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/lumen/bootstrap.min.css'>Newspaper</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/solar/bootstrap.min.css'>Solar</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css'>Cosmo</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css'>Flatly</option>
                <option value='//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/yeti/bootstrap.min.css'>Yeti</option>
            </select>

            <div class='setup-form-buttons'>
                <input type='submit' class='btn btn-success' value='{{ __('setup.submit') }}'>
                <input type='reset' class='btn btn-warning' value='{{ __('setup.clear') }}'>
            </div>
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
        </form>
    </div>

    <div class='col-md-3'></div>
</div>

<div class='setup-footer well'>
    {{ __('setup.footer.opensource', ['link1' => '<a href="https://opensource.org/osd" target="_"blank">' . __('setup.footer.opensource.link1') . '</a>', 'link2' => '<a href="//www.gnu.org/copyleft/gpl.html">' . __('setup.footer.opensource.link2') . '</a>']) }}

    <div>
        {{ __('setup.footer.version', ['version' => env('VERSION'), 'month' => env('VERSION_RELMONTH'), 'day' => env('VERSION_RELDAY'), 'year' => env('VERSION_RELYEAR')]) }} -
        <a href='//github.com/cydrobolt/polr' target='_blank'>Github</a>

        <div class='footer-well'>
            &copy; {{ __('setup.footer.copyright', ['year' => env('VERSION_RELYEAR')]) }}
            <a class='footer-link' href='//cydrobolt.com' target='_blank'>Chaoyi Zha</a> {{ __('setup.footer.ampersand') }}
            <a class='footer-link' href='//github.com/Cydrobolt/polr/graphs/contributors' target='_blank'>{{ __('setup.footer.other') }}</a>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="/js/bootstrap.min.js"></script>
<script src='/js/angular.min.js'></script>
<script src='/js/base.js'></script>
<script src='/js/SetupCtrl.js'></script>
@endsection
