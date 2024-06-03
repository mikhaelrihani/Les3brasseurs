<?php
namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
   
    private $authToken ='%env(TWILIO_AUTH_TOKEN)%';
    private $accountSid= '%env(TWILIO_ACCOUNT_SID)%';
    private $from = '%env(TWILIO_FROM_NUMBER)%';    

    public function sendMms(string $to, string $body, string $mediaUrl): string
    {
        $client = new Client($this->accountSid, $this->authToken);
        try {
           $mms =  $client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $body,
                    'mediaUrl' => $mediaUrl
                ]
            );
            
        } catch (\Exception $e) {
            throw new \Exception("Error sending MMS: " . $e->getMessage());
        }
       return  $mms;
    }
}
