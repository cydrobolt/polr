<h3>Hello {{$username}}!</h3>

<p>
    You may use the link located in this email to reset your password for your
    account at {{env('APP_NAME')}}.
</p>

<a href='{{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/reset_password/{{$username}}/{{$recovery_key}}'>
    {{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}/reset_password/{{$username}}/{{$recovery_key}}
</a>

<br />

<p>Thanks,</p>
<p>The {{env('APP_NAME')}} team.</p>

--
<br />
You received this email because someone with the IP {{$ip}} requested a password reset
for an account at {{env('APP_PROTOCOL')}}{{env('APP_ADDRESS')}}. If this was not you,
you may ignore this email.
