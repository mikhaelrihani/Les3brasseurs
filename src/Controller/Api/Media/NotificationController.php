<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Service\TwilioService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Bridge\Twilio\TwilioTransport;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends MainController
{

    private $params;
    private $twilioTransport;
    private $texter;
    private $twilioService;

    public function __construct(ParameterBagInterface $params, TwilioTransport $twilioTransport, TexterInterface $texter, TwilioService $twilioService)
    {
        $this->params = $params;
        $this->twilioTransport = $twilioTransport;
        $this->texter = $texter;
        $this->twilioService = $twilioService;
    }

    #[Route(path: '/send-sms', name: 'send_sms')]
    public function sendSms(Request $request): Response
    {
        dd("hey");
        $from = $this->params->get('app.twilio_from_number');
        $to = $request->request->get('to');
        $body = $request->request->get('body');

        try {
            $to = $this->params->get('app.admin_phone_number');
            $body = 'Omika is back on track!';

            $sms = new SmsMessage(
                $to,
                $body,
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

    #[Route(path: '/send-mms', name: 'send_mms', methods: "POST")]
    public function sendMms(Request $request): Response
    {
        $to = $request->request->get('to');
        $body = $request->request->get('body');
        $fileUrl = $request->request->get('fileUrl');

        if (!$to || !$body || !$fileUrl) {
            return $this->json(['error' => 'Missing required parameters.'], Response::HTTP_BAD_REQUEST);
        }

        try {

            $sentMms = $this->twilioService->sendMms($to, $body, $fileUrl);

            return $this->json(['message' => 'MMS sent successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

