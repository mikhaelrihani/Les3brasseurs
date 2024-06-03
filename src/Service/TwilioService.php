<?php
namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private $from;
    private $client;

    public function __construct(string $accountSid, string $authToken, string $from)
    {
        $this->from = $from;
        
        $this->client = new Client($accountSid, $authToken);
    }

    public function sendMms(string $to, string $body, string $mediaUrl): string
    {
        try {
            $message = $this->client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $body,
                    'mediaUrl' => [$mediaUrl]
                ]
            );
            return $message; 
        } catch (\Exception $e) {
            throw new \Exception("Error sending MMS: " . $e->getMessage());
        }
    }
}
