<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Service\TwilioService;
use App\Service\UploadService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Bridge\Twilio\TwilioTransport;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/notification')]
class NotificationController extends MainController
{

    private $params;
    private $twilioTransport;
    private $texter;
    private $twilioService;
    private $uploadDirectory;
   


    public function __construct(
        ParameterBagInterface $params,
        TwilioTransport $twilioTransport,
        TexterInterface $texter,
        TwilioService $twilioService,
      
    ) {
        $this->params = $params;
        $this->twilioTransport = $twilioTransport;
        $this->texter = $texter;
        $this->twilioService = $twilioService;
        $this->uploadDirectory = $params->get('upload_directory');
    
    }

    #[Route('/sendSms', name: 'sendSms', methods: ['POST'])]

    public function sendSms(Request $request): Response
    {
        $from = $this->params->get('app.twilio_from_number');
        $to = $request->request->get('to');
        $body = $request->request->get('body');

        try {

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

    // Résumé du flux d'envoi de fichiers via MMS avec Twilio.
    // L'utilisateur upload un fichier ou sélectionne un fichier depuis un dossier externe.
    // Le media est téléchargé dans public/upload.
    // Une URL publique est générée pour le media du mms.
    // Le MMS est envoyé en utilisant l'URL publique.
    // Le fichier est supprimé du dossier public/upload après l'envoi.
    
    #[Route('/sendMms', name: 'app_api_notification_sendMms', methods: ['POST'])]
    public function sendMms(Request $request): Response
    {
        $to = $request->request->get('to');
        $file = $request->files->get('file');
        $body = $request->request->get('body');

        if (!$to || !$body || !$file) {
            return $this->json(['error' => 'Missing required parameters.'], Response::HTTP_BAD_REQUEST);
        }
        $filename = $file->getClientOriginalName();
        //generate public media url 
        $mediaUrl = $this->getParameter('public_path') . '/download.php?file=' . urlencode($filename);
   
        try {
           $this->twilioService->sendMms($to, $body, $mediaUrl);
            // remove temporary file
           unlink($this->uploadDirectory . '/' . $filename);
            return $this->json(['message' => 'MMS sent successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to send MMS: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}