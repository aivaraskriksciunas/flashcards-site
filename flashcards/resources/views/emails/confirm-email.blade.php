<p>Hello</p>

<br>

<p>
    We are happy you have decided to try Aktulibre. 
    Please <a href="{{ $emailConfirmation->getPublicUrl() }}">click here</a> 
    to confirm your email and activate your account.
</p>

<br>

<p>If the link above does not work, paste this text into your browser:</p>
<br>
<a href="{{ $emailConfirmation->getPublicUrl() }}">{{ $emailConfirmation->getPublicUrl() }}</a> 

<br>
<br>

<p>
    Sincerely, <br>
    Aktulibre team
</p>