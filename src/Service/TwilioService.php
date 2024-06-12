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

    public function sendMms(string $to, string $body, string $mediaUrl): void
    {
        try {

            $response = $this->client->messages->create(
                $to,
                [
                    'from'     => $this->from,
                    'body'     => PHP_EOL. $body . PHP_EOL,
                    'mediaUrl' => [$mediaUrl]
                ]

            );

        } catch (\Exception $e) {
            throw new \Exception("Error sending MMS: " . $e->getMessage());
        }
    }
}
