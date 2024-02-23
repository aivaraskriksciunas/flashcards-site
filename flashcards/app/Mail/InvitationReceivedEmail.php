<?php

namespace App\Mail;

use App\Models\Invitation;
use Google\Service\BigtableAdmin\Split;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Invitation $invitation
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->getSubjectLine(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation-received',
            with: [
                'creator_name' => ucwords( $this->invitation->creator->name ),
                'receiver_name' => ucwords( $this->invitation->name ),
                'org_name' => ucwords( $this->invitation->organization->name ),
                'valid_until' => $this->invitation->valid_until,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    private function getSubjectLine() : string
    {
        $name = $this->invitation->creator->name;
        $first_name = ucfirst( explode( ' ', $name )[0] );
        $org = ucfirst( $this->invitation->organization->name );
        return "{$first_name} has invited you to join their organization '$org'";
    }
}
