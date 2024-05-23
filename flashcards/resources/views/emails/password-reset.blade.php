<p>Hello</p>

<br>

<p>
    We have received a request to reset your account's password. 
    Please <a href="{{ $reset->getPublicUrl() }}">click here</a> 
    to create a new password. 
</p>

<br>

<p>Alternatively if the link does not work, paste this url into your browser:</p>
<br>
<a href="{{ $reset->getPublicUrl() }}">{{ $reset->getPublicUrl() }}</a> 
<br>
<p>If you did not intend to reset your password, please ignore this email. The link provided above will expire in 30 minutes. If you suspect an abuse of our system, kindly please inform us at info@aktulibre.eu.</p>

<br>
<br>

<p>
    Sincerely, <br>
    Aktulibre team
</p>