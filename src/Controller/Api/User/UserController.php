<?php

namespace App\Controller\Api\User;

use App\Repository\UserInfosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/user/getUsers', name: 'app_api_user_getUsers', methods: 'GET')]
    public function GetUsers(UserInfosRepository $userInfosRepository): JsonResponse
    {
        $users = $userInfosRepository->findAll();
        return $this->json([
            'users' => $users,
        ], 200, [], ["groups" => "userWithoutRelation"]);
    }

    #[Route('/api/user/getUsersWithRelation', name: 'app_api_user_getUsersWithRelation', methods: 'GET')]
    public function GetUsersWithRelations(UserInfosRepository $userInfosRepository): JsonResponse
    {
        $users = $userInfosRepository->findAll();
        return $this->json([
            'users' => $users,
        ], 200, [],);
    }
}
