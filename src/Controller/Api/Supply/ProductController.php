<?php

namespace App\Controller\Api\Supply;

use App\Controller\MainController;
use App\Entity\Product;
use App\Entity\Room;
use App\Entity\Supplier;
use App\Entity\SupplyType;
use App\Repository\ProductRepository;
use App\Service\ImageEntityService;
use App\Service\ImageKitService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/products')]
class ProductController extends MainController
{
    //! GET PRODUCTS

    #[Route('/', name: 'app_api_product_getProducts', methods: 'GET')]
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        if (!$products) {
            return $this->json(["error" => "There are no products"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($products, Response::HTTP_OK, [], ["groups" => "productWithRelation"]);

    }

    //! GET PRODUCT

    #[Route('/{id}', name: 'app_api_product_getProduct', methods: 'GET')]
    public function getProduct(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["error" => "The product with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($product, Response::HTTP_OK, [], ["groups" => "productWithRelation"]);
    }

    //! POST PRODUCT

    #[Route('/post', name: 'app_api_product_postProduct', methods: 'POST')]
    public function postProduct(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
    ): JsonResponse {

        $jsonContent = $request->getContent();
        $product = $serializer->deserialize($jsonContent, Product::class, 'json');

        $dataErrors = $this->validatorError->returnErrors($product);
        if ($dataErrors) {
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $date = new DateTimeImmutable();
        $product->setCreatedAt($date);
        $product->setUpdatedAt($date);

        $supplyType = json_decode($jsonContent)->SupplyType->id;
        $supplyType = $em->getRepository(SupplyType::class)->find($supplyType);
        $product->setSupplyType($supplyType);

        $em->persist($product);
        $em->flush();

        return $this->json(
            [$product],
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl(
                    'app_api_product_postProduct',
                    ["id" => $product->getId()]
                )
            ],
            ["groups" => "productWithRelation"]

        );
    }

    //! Add Supplier to Product

    #[Route('/{id}/addSupplier/{supplierId}', name: 'app_api_product_addSupplier', methods: 'PUT')]
    public function addSupplierToProduct(
        int $id,
        int $supplierId,
        ProductRepository $productRepository,
        EntityManagerInterface $em,
    ): JsonResponse {

        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["error" => "The product with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }

        $supplier = $em->getRepository(Supplier::class)->find($supplierId);
        if (!$supplier) {
            return $this->json(["error" => "The supplier with ID " . $supplierId . " does not exist"], Response::HTTP_BAD_REQUEST);
        }

        $product->addSupplier($supplier);
        $em->flush();

        return $this->json($product, Response::HTTP_OK, [], ["groups" => "productWithRelation"]);
    }

    //! Add Room to Product

    #[Route('/{id}/addRoom/{roomId}', name: 'app_api_product_addRoom', methods: 'PUT')]

    public function addRoomToProduct(
        int $id,
        int $roomId,
        ProductRepository $productRepository,
        EntityManagerInterface $em,
    ): JsonResponse {

        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["error" => "The product with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }

        $room = $em->getRepository(Room::class)->find($roomId);
        if (!$room) {
            return $this->json(["error" => "The room with ID " . $roomId . " does not exist"], Response::HTTP_BAD_REQUEST);
        }

        $product->addRoom($room);
        $em->flush();

        return $this->json($product, Response::HTTP_OK, [], ["groups" => "productWithRelation"]);
    }

    //! Add Picture to Product

    #[Route('/{id}/addPicture', name: 'app_api_product_addPicture', methods: 'POST')]

    public function addPictureToProduct(
        int $id,
        Request $request,
        ImageKitService $imageKit,
        ImageEntityService $imageEntityService,
    ): JsonResponse {

        $pictures = $request->files->all();
        $uploadedPictures = $imageKit->uploadUniquePictures($pictures);
        $product = $imageEntityService->addPictures(Product::class, $id, $uploadedPictures);

        return $this->json($product, Response::HTTP_OK, [], ["groups" => "productWithRelation"]);

    }


    //! PUT PRODUCT

    #[Route('/{id}', name: 'app_api_product_putProduct', methods: 'PUT')]
    public function putProduct(
        int $id,
        SerializerInterface $serializer,
        ProductRepository $productRepository,
        EntityManagerInterface $em,
        Request $request,
    ): JsonResponse {

        $productToUpdate = $productRepository->find($id);
        if (!$productToUpdate) {
            return $this->json(["error" => "The product with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }

        $jsonContent = $request->getContent();
        $updatedProduct =
            $serializer->deserialize($jsonContent, Product::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $productToUpdate
            ]);

        $dataErrors = $this->validatorError->returnErrors($updatedProduct);
        if ($dataErrors) {
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $updatedProduct->setUpdatedAt(new DateTimeImmutable());
        $em->flush();

        return $this->json($updatedProduct, Response::HTTP_OK, [], ["groups" => "productWithRelation"]);
    }

    //! DELETE PRODUCT

    #[Route('/{id}', name: 'app_api_product_deleteProduct', methods: 'DELETE')]
    public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $em): JsonResponse
    {
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["error" => "The product with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        try {
            // Supprimer manuellement les fournisseurs associés à ce produit
            // $suppliers = $product->getSuppliers();
            // foreach ($suppliers as $supplier) {
            //     $product->getSuppliers()->removeElement($supplier);

            // }
            $em->remove($product);
        } catch (ORMInvalidArgumentException $e) {

            return $this->json(["error" => "Failed to delete the product with ID " . $id . ""], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $em->flush();
        return $this->json("The product with ID " . $id . " has been deleted successfully", Response::HTTP_OK);
    }

}
