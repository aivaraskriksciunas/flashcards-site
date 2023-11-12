<?php 

namespace App\Services\Mail;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\Address;
use Mailjet\Client as MailjetClient;
use \Mailjet\Resources;
use Illuminate\Support\Facades\Log;

class MailjetTransport extends AbstractTransport 
{
    private MailjetClient $client;

    public function __construct(
        private string $api_key,
        private string $secret_key,
    )
    {
        parent::__construct();

        $this->client = new MailjetClient( $api_key, $secret_key, true, ['version' => 'v3.1'] );
    }

    protected function doSend( SentMessage $message ): void 
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $email->getFrom()[0]->getAddress(),
                        'Name' => $email->getFrom()[0]->getName(),
                    ],
                    'To' => collect( $email->getTo() )->map( function ( Address $address ) {
                        return [
                            'Email' => $address->getAddress(),
                            'Name' => $address->getName(),
                        ];
                    })->all(),
                    'Subject' => $email->getSubject(),
                    'HTMLPart' => $email->getHtmlBody(),
                ]
            ]
        ];

        $response = $this->client->post( Resources::$Email, [ 'body' => $body ] );
        if ( !$response->success() )
        {
            Log::error( "Could not send email to {email}. Error: {error}", [
                'email' => count( $email->getTo() ) ? $email->getTo()[0]->getAddress() : 'Undefined',
                'error' => $response->getBody(),
            ] );
        }
    }

    public function __toString(): string
    {
        return 'mailjet';
    }
}