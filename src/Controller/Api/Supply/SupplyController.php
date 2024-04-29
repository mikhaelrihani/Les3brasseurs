<?php

namespace App\Controller\Api\Supply;

use App\Controller\MainController;
use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use App\Service\UserService;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/suppliers')]
class SupplyController extends MainController
{
    //! GET SUPPLIERS

    #[Route('/', name: 'app_api_supply_getSupppliers', methods: 'GET')]
    public function getSuppliers(SupplierRepository $supplierRepository): JsonResponse
    {
        $suppliers = $supplierRepository->findAll();
        if (!$suppliers) {
            return $this->json(["error" => "There are no suppliers"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($suppliers, Response::HTTP_OK, [], ["groups" => "supplyWithRelation"]);

    }

    //! GET SUPPLIER

    #[Route('/{id}', name: 'app_api_supply_getSuppplier', methods: 'GET')]
    public function getSupplier(int $id, SupplierRepository $supplierRepository): JsonResponse
    {
        $supplier = $supplierRepository->find($id);

        if (!$supplier) {
            return $this->json(["error" => "The supplier with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($supplier, Response::HTTP_OK, [], ["groups" => "supplyWithRelation"]);
    }

    //! POST SUPPLIER

    #[Route('/post', name: 'app_api_supply_postSuppplier', methods: 'POST')]
    public function postSuppplier(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
    ): JsonResponse {

        $jsonContent = $request->getContent();
        $supplier = $serializer->deserialize($jsonContent, Supplier::class, 'json');

        $dataErrors = $this->validatorError->returnErrors($supplier);
        if ($dataErrors) {
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $date = new DateTimeImmutable();
        $supplier->setCreatedAt($date);
        $supplier->setUpdatedAt($date);

        $em->persist($supplier);

        try {
            $em->flush();
        } catch (UniqueConstraintViolationException $e) {

            return new JsonResponse(['error' => 'A supplier with the same name already exists.'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            [$supplier],
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl(
                    'app_api_product_postProduct',
                    ["id" => $supplier->getId()]
                )
            ],
            ["groups" => "supplyWithRelation"]

        );
    }

    //! AddStaff

    #[Route('/{id}/Staff', name: 'app_api_supply_addStaff', methods: 'POST')]

    public function addStaff(
        int $id,
        UserService $userService,
        Request $request,
        EntityManagerInterface $em,
        SupplierRepository $supplierRepository
    ): JsonResponse {

        $userData = json_decode($request->getContent(), true);

        $existingUser = $userService->getUserByEmail($userData);

        if ($existingUser) {
            $staff = $existingUser;
        } else {

            $staff = $userService->createUser($request)->getUser();
        }
        if (is_array($staff))
            return $this->json($staff, Response::HTTP_UNPROCESSABLE_ENTITY);

        $supplier = $supplierRepository->find($id);
        $supplier->addStaff($staff);
        $supplier->setUpdatedAt(new DateTimeImmutable());

        $em->persist($supplier);
        $em->flush();

        return $this->json(
            [$staff],
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl(
                    "app_api_user_getOneUser",
                    ["id" => $supplier->getId()]
                )
            ],
            ["groups" => "userWithRelation"]

        );
    }


    //! POST SUPPLIER product



    //! PUT SUPPLIER

    #[Route('/{id}', name: 'app_api_supply_putSuppplier', methods: 'PUT')]
    public function putSupplier(
        int $id,
        SerializerInterface $serializer,
        SupplierRepository $supplierRepository,
        EntityManagerInterface $em,
        Request $request,
    ): JsonResponse {

        $supplierToUpdate = $supplierRepository->find($id);
        if (!$supplierToUpdate) {
            return $this->json(["error" => "The supplier with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        $jsonContent = $request->getContent();

        $updatedSupplier =
            $serializer->deserialize($jsonContent, Supplier::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $supplierToUpdate
            ]);

        $dataErrors = $this->validatorError->returnErrors($updatedSupplier);
        if ($dataErrors) {
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $em->flush();

        return $this->json($updatedSupplier, Response::HTTP_OK, [], ["groups" => "supplyWithRelation"]);
    }

    //! DELETE SUPPLIER

    #[Route('/{id}', name: 'app_api_supply_deleteSuppplier', methods: 'DELETE')]
    public function deleteSupplier(int $id, SupplierRepository $supplierRepository, EntityManagerInterface $em): JsonResponse
    {
        $supplier = $supplierRepository->find($id);
        if (!$supplier) {
            return $this->json(["error" => "The supplier with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        try {
            $em->remove($supplier);
        } catch (ORMInvalidArgumentException $e) {

            return $this->json(["error" => "Failed to delete the supplier with ID " . $id . ""], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $em->flush();
        return $this->json("The supplier with ID " . $id . " has been deleted successfully", Response::HTTP_OK, [], ["groups" => "supplyWithRelation"]);
    }

}
