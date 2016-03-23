<h3>Hello {{$username}}!</h3>

<p>Thanks for registering at {{env('APP_NAME')}}. To use your account,
you will need to activate it by clicking the following link:</p>

<br />

<a href='{{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/activate/{{$username}}/{{$recovery_key}}'>
    {{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/activate/{{$username}}/{{$recovery_key}}
</a>

<br />

<p>Thanks,</p>
<p>The {{env('APP_NAME')}} team.</p>

--
<br />
You received this email because someone with the IP {{$ip}} signed up
for an account at {{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}. If this was not you,
you may ignore this email.
