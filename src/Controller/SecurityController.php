<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Bridge\Twilio\TwilioTransport;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
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

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/send-sms', name: 'send_sms')]
    public function sendSms(): Response
    {
        try {
            $adminPhoneNumber = $this->params->get('app.admin_phone_number');
            $from = $this->params->get('app.twilio_from_number');

            $sms = new SmsMessage(
                $adminPhoneNumber,
                'Omika is back!',
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
