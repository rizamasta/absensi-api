<div class="password-reset">
Hello {{ $data->name }},
<p>
We have received a request to reset the password for your account on BAKKAT. Please click the link below to complete your password reset.
</p>
<a  href="{{ $data->url }}">Reset Link</a>

<p>
If you cannot click the link, please try pasting the text into your browser.<br/>
If you did not make this request you can ignore this email.
</p>
</div>