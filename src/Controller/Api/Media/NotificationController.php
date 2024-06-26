<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Service\TwilioService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/notification')]
class NotificationController extends MainController
{
    private $twilioService;
    private $uploadDirectory;

    public function __construct(ParameterBagInterface $params, TwilioService $twilioService, )
    {
        $this->twilioService = $twilioService;
        $this->uploadDirectory = $params->get('upload_directory');
    }

    #[Route('/sendSms', name: 'app_api_notification_sendSms', methods: ['POST'])]
    public function sendSms(Request $request): Response
    {
        $parameters = $this->getTwilioParameters($request);
        try {
            $this->twilioService->sendsms($parameters[ 'to' ], $parameters[ 'body' ]);
            return $this->json(['message' => 'SMS sent successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to send SMS: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/sendMms', name: 'app_api_notification_sendMms', methods: ['POST'])]
    public function sendMms(Request $request): Response
    {
        $parameters = $this->getTwilioParameters($request);
        if ($parameters instanceof JsonResponse) {
            return $parameters;
        }
        try {
            $this->twilioService->sendMms($parameters[ 'to' ], $parameters[ 'body' ], $parameters[ 'mediaUrl' ]);
            // remove temporary file
            $fileTempPath = $this->uploadDirectory . '/' . $parameters[ 'fileName' ];
            if (file_exists($fileTempPath)) {
                unlink($fileTempPath);
            }
            return $this->json(['message' => 'MMS sent successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/sendWhatsapp', name: 'app_api_notification_sendWhatsapp', methods: ['POST'])]
    public function sendWhatsapp(Request $request): Response
    {
        $parameters = $this->getTwilioParameters($request);
        try {
            $this->twilioService->sendWhatsapp($parameters[ 'to' ], $parameters[ 'body' ], $parameters[ 'mediaUrl' ]);
            return $this->json(['message' => 'Whatsapp sent successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to send Whatsapp: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTwilioParameters(Request $request)
    {
        $to = $request->request->get('to');
        $body = $request->request->get('body');

        if (!$to || !$body) {
            return $this->json(['error' => 'Missing required parameters.'], Response::HTTP_BAD_REQUEST);
        }

        $parameters = [];
        $parameters[ 'to' ] = $to;
        $parameters[ 'body' ] = $body;

        $file = $request->files->get('file');
        if ($file) {
            $fileName = $file->getClientOriginalName();
            $file->move($this->uploadDirectory, $fileName);
            //generate public media url 
            $mediaUrl = $this->getParameter('public_path') . "/upload/" . urlencode($fileName);
        } else {
            $fileName = $request->request->get('fileName');
        }
        $parameters[ 'fileName' ] = $fileName;
        //generate external public media url 
        $mediaUrl = $this->getParameter('public_path') . "/Files/". urlencode($fileName);
        $parameters[ 'mediaUrl' ] = $mediaUrl;

        return $parameters;
    }
}