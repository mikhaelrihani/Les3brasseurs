<?php

namespace App\Controller\Api\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Bridge\Twilio\TwilioTransport;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{

    private $params;
    private $twilioTransport;
    private $texter;

    public function __construct(ParameterBagInterface $params, TwilioTransport $twilioTransport, TexterInterface $texter)
    {
        $this->params = $params;
        $this->twilioTransport = $twilioTransport;
        $this->texter = $texter;
    }
    #[Route(path: '/send-sms', name: 'send_sms')]
    public function sendSms(): Response
    {
        try {
            $adminPhoneNumber = $this->params->get('app.admin_phone_number');
            $from = $this->params->get('app.twilio_from_number');

            $sms = new SmsMessage(
                $adminPhoneNumber,
                'Omika is!',
                $from
            );

            // Option 1: Use the texter service for async handling
            //$this->texter->send($sms);

            // Option 2: Use the Twilio transport directly for synchronous handling
            $sentMessage = $this->twilioTransport->send($sms);

            return new Response('SMS sent successfully.');
        } catch (\Exception $e) {
            return new Response('Failed to send SMS: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
