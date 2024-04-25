<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Entity\UserInfos;
use App\Repository\UserInfosRepository;
use App\Service\ValidatorErrorService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Uid\Uuid;

class UserController extends AbstractController
{
    private $validatorError;

    public function __construct(
        ValidatorErrorService $validatorError
    ) {
        $this->validatorError = $validatorError;
    }

    //! Get USERS

    #[Route('/api/user/getUsers', name: 'app_api_user_getUsers', methods: 'GET')]
    public function getUsers(UserInfosRepository $userInfosRepository): JsonResponse
    {
        $users = $userInfosRepository->findAll();
        if (!$users) {
            return $this->json(["error" => "There are no users"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($users, Response::HTTP_OK, [], ["groups" => "userWithoutRelation"]);

    }

    //! Get USERS WITH INFOS

    #[Route('/api/user/getUsersWithInfos', name: 'app_api_user_getUsersWithInfos', methods: 'GET')]
    public function getUsersWithInfos(UserInfosRepository $userInfosRepository): JsonResponse
    {
        $users = $userInfosRepository->findAll();
        return $this->json([
            'users' => $users,
        ], 200, [], );
    }

    //! Get USER

    #[Route('/api/user/getOneUser/{id}', name: 'app_api_user_getOneUser', methods: 'GET')]
    public function getOneUser(int $id, UserInfosRepository $userInfosRepository): JsonResponse
    {
        $user = $userInfosRepository->find($id);
        if (!$user) {
            return $this->json(["error" => "The user with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($user, Response::HTTP_OK, [], ["groups" => "userWithoutRelation"]);
    }

    //! Get USER WITH INFOS

    #[Route('/api/user/getUserWithInfos/{id}', name: 'app_api_user_getUserWithInfos', methods: 'GET')]
    public function getUserWithInfos(int $id, UserInfosRepository $userInfosRepository): JsonResponse
    {
        $user = $userInfosRepository->find($id);
        if (!$user) {
            return $this->json(["error" => "The user with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($user, Response::HTTP_OK, [], );
    }

    //! POST USER

    #[Route('/api/user/postUser', name: 'app_api_user_postUser', methods: 'POST')]
    public function postUser(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher
    ): JsonResponse {

        // Deserialize JSON content into User object
        $jsonContent = $request->getContent();
        $user = $serializer->deserialize($jsonContent, UserInfos::class, 'json');

        // Hashing the password
        $password = $user->getUser()->getPassword();
        $user->getUser()->setPassword($userPasswordHasher->hashPassword($user->getUser(), $password));

        // Validate User object  or return validation errors
        $dataErrors = $this->validatorError->returnErrors($user);
        if ($dataErrors) {
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Post user and save changes into database
        $em->persist($user);
        $em->flush();

        // Return json with datas of new user 
        return $this->json(
            [$user],
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl(
                    "app_api_user_getOneUser",
                    ["id" => $user->getId()]
                )
            ],

        );
    }

    //! PUT USER
    #[Route('/api/user/putUser/{id}', name: 'app_api_user_putUser', methods: 'PUT')]
    public function putUser(
        int $id,
        SerializerInterface $serializer,
        UserInfosRepository $userInfosRepository,
        EntityManagerInterface $em,
        Request $request,
    ): JsonResponse {
        // Find user info or return error
        $userInfoToUpdate = $userInfosRepository->find($id);
        $initialUser = $userInfoToUpdate->getUser();
        if (!$userInfoToUpdate) {
            return $this->json(["error" => "The user info with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }

        // Deserialize JSON content into same object to update
        $jsonContent = $request->getContent();
        $dataArray = json_decode($jsonContent, true);
        $updatedUserInfo =
            $serializer->deserialize($jsonContent, UserInfos::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $userInfoToUpdate
            ]);

        // Validate user info or return validation errors
        $dataErrors = $this->validatorError->returnErrors($updatedUserInfo);
        if ($dataErrors) {
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Update property "updated_at"
        $updatedUserInfo->setUpdatedAt(new DateTimeImmutable());

        // We take care of the ID consistency of the user info and the user
        $updatedUserInfo->setUser($initialUser);

        // We update the user properties
        $initialUser
            ->setFirstname($dataArray[ 'user' ][ 'firstname' ])
            ->setSurname($dataArray[ 'user' ][ 'surname' ])
            ->setSlug($dataArray[ 'user' ][ 'slug' ])
            ->setUpdatedAt($updatedUserInfo->getUser()->getUpdatedAt());
        // Validate user data
        $userDataErrors = $this->validatorError->returnErrors($initialUser);
        if ($userDataErrors) {
            return $this->json($userDataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Save changes into database
        $em->flush();

        // Return json with updated user info data 
        return $this->json($userInfoToUpdate, Response::HTTP_OK);
    }



    //! DELETE USER
    #[Route('/api/user/deleteUser/{id}', name: 'app_api_user_deleteUser', methods: 'DELETE')]
    public function deleteUser(int $id, UserInfosRepository $userInfosRepository): JsonResponse
    {
        // Find user or return error
        $user = $userInfosRepository->find($id);
        if (!$user) {
            return $this->json(["error" => "The user with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }

        // Remove user and save changes into database or return error
        try {
            $userInfosRepository->remove($user, true);

        } catch (ORMInvalidArgumentException $e) {

            return $this->json(["error" => "Failed to delete the user with ID " . $id . ""], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        //return json with success custom message
        return $this->json("The user with ID " . $id . " has been deleted successfully", Response::HTTP_OK);
    }



}
