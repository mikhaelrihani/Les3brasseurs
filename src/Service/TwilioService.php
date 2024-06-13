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

    public function sendSms(string $to, string $body): void
    {
        try {
            $sms = $this->client->messages->create(
                $to,
                [
                    'from'     => $this->from,
                    'body'     => PHP_EOL. $body . PHP_EOL,
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception("Error sending MMS: " . $e->getMessage());
        }
    }
    public function sendMms(string $to, string $body, string $mediaUrl): void
    {
        try {
            $mms = $this->client->messages->create(
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

    public function sendWhatsapp(string $to, string $body, string $mediaUrl): void
    {
        try {
            $Whatsapp = $this->client->messages->create(
                "whatsapp:".$to,
                [
                    'from'     => "whatsapp:".$this->from,
                    'body'     => PHP_EOL. $body . PHP_EOL,
                    'mediaUrl' => [$mediaUrl]
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception("Error sending whatsapp: " . $e->getMessage());
        }
    }
   
}
