<p>Dear {{ $receiver_name }},</p>

<br>

<p>
    {{ $creator_name }} has invited you to join their organization "{{ $org_name }}".
    Please click <a href="{{ $invitation->getAcceptationUrl() }}">here</a> to accept the invitation. 
    This invitation is valid until {{ $valid_until->format( 'F j, H:i' ) }}.
</p>

<br>

<p>If the link above does not work, paste this text into your browser:</p>
<br>
<a href="{{ $invitation->getAcceptationUrl() }}">{{ $invitation->getAcceptationUrl() }}</a> 

<br>

<p>
    If you believe you have received this message by accident, please ignore it. 
    You may also email us at info@aktulibre.eu to report an abuse of our system.
</p>

<br>

<p>
    Sincerely, <br>
    Aktulibre team
</p>