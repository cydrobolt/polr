<h3>{{ __('mail.password_reset.greet', ['username' => $username]) }}</h3>

<p>{{ __('mail.password_reset.preset_confirm', ['app' => env('APP_NAME')]) }}</p>

<a href='{{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/reset_password/{{$username}}/{{$recovery_key}}'>
    {{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/reset_password/{{$username}}/{{$recovery_key}}
</a>

<br />

<p>{{ __('mail.password_reset.thanks') }}</p>
<p>{{ __('mail.password_reset.team', ['app' => env('APP_NAME')]) }}</p>

--
<br />
{{ __('mail.password_reset.footer', ['ip' => $ip, 'url' => env('APP_PROTOCOL') . env('APP_ADDRESS')]) }}

