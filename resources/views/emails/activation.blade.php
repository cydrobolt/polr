<h3>{{ __('mail.activation.greet', ['username' => $username]) }}</h3>

<p>{{ __('mail.activation.register_confirm', ['app' => env('APP_NAME')]) }}</p>

<br />

<a href='{{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/activate/{{$username}}/{{$recovery_key}}'>
    {{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/activate/{{$username}}/{{$recovery_key}}
</a>

<br />

<p>{{ __('mail.activation.thanks') }}</p>
<p>{{ __('mail.activation.team', ['app' => env('APP_NAME')]) }}</p>

--
<br />
{{ __('mail.activation.footer', ['ip' => $ip, 'url' => env('APP_PROTOCOL') . env('APP_ADDRESS')]) }}
