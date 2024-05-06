<?php



namespace App\Controller\Api\Supply;

use App\Controller\MainController;
use App\Entity\Supplier;
use App\Repository\OrderRepository;
use App\Repository\SupplierRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/orders')]
class OrderController extends MainController
{

    //! GET ORDERS

    #[Route('/', name: 'app_api_order_getOrders', methods: 'GET')]
    public function getOrders(OrderRepository $orderRepository): JsonResponse
    {
        $orders = $orderRepository->findAll();
        if (!$orders) {
            return $this->json(["error" => "There are no orders"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($orders, Response::HTTP_OK, [], ["groups" => "orderWithRelation"]);

    }

    //! GET ORDER

    #[Route('/{id}', name: 'app_api_order_getOrder', methods: 'GET', requirements: ['id' => '\d+'])]
    public function getOrder(int $id, OrderRepository $orderRepository): JsonResponse
    {
        $order = $orderRepository->find($id);

        if (!$order) {
            return $this->json(["error" => "The order with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($order, Response::HTTP_OK, [], ["groups" => "orderWithRelation"]);
    }

    //! GET ORDERS BY

    #[Route('/by', name: 'app_api_order_getOrdersBy', methods: ['GET'])]
    public function getOrdersBy(Request $request, OrderRepository $orderRepository): JsonResponse
    {
        $queryParams = $request->query->all();
        $queryBuilder = $orderRepository->createQueryBuilder('o');
       
        if (empty($queryParams)) {
            return $this->json(["error" => "No query parameter provided"], Response::HTTP_BAD_REQUEST);
        }

        // Construire la requête en fonction des paramètres de requête fournis
        foreach ($queryParams as $key => $value) {
            if ($this->isJson($value)) {
                $dateParams = json_decode($value, true);
            }
            if ($key === 'date')
                $this->addDateFilter($queryBuilder, $dateParams);

            if ($key === 'period')
                $this->addPeriodFilter($queryBuilder, $dateParams);

            if ($key === 'supplierName') {
                $queryBuilder->andWhere("o.supplierName = :supplierName")->setParameter('supplierName', $value);
            }

        }
        // Exécuter la requête
        $orders = $queryBuilder->getQuery()->getResult();
        
        if (empty($orders)) {
            return $this->json(["error" => "No orders found for the provided query parameters"], Response::HTTP_NOT_FOUND);
        }

        return $this->json($orders, Response::HTTP_OK, [], ["groups" => "orderWithRelation"]);
    }


    //! POST ORDER

    #[Route('/post', name: 'app_api_supplier_postSuppplier', methods: 'POST')]
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

    //! PUT ORDER

    #[Route('/{id}', name: 'app_api_supplier_putSuppplier', methods: 'PUT')]
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

    //! DELETE ORDER

    #[Route('/{id}', name: 'app_api_supplier_deleteSuppplier', methods: 'DELETE')]
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


