<?php

namespace App\Controller\Api\User;

use App\Repository\UserInfosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/user/user', name: 'app_api_user_user', methods : 'GET')]
    public function index(UserInfosRepository $userInfosRepository): JsonResponse
    {
        $users = $userInfosRepository->findAll();
        return $this->json([
            'users' => $users,
        ]); 
        // return $this->json([
        //     'users' => $users,
        // ], 200, [], ["Groups" => "userWithoutRelation"]);
    }
}
