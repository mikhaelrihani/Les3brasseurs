<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserInfos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;


class UserService
{
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    private UserPasswordHasherInterface $userPasswordHasher;
    private ValidatorErrorService $validatorError;

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $userPasswordHasher,
        ValidatorErrorService $validatorError
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->validatorError = $validatorError;
    }

    public function createUser(Request $request)
    {
        $jsonContent = $request->getContent();
        $userInfos = $this->serializer->deserialize($jsonContent, UserInfos::class, 'json');

        $password = $userInfos->getUser()->getPassword();
        $userInfos->getUser()->setPassword($this->userPasswordHasher->hashPassword($userInfos->getUser(), $password));

        $dataErrors = $this->validatorError->returnErrors($userInfos);

        if ($dataErrors) {
            return $dataErrors;
        }

        $this->em->persist($userInfos);
        $this->em->flush();

        return $userInfos;
    }

    public function getUserByEmail($userData): ?User
    {
         $userInfo = $this->em->getRepository(UserInfos::class)->findOneBy(['email' => $userData['email']]);
         return  $userInfo->getUser();
    }
    
}
