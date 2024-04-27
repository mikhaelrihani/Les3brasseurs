<?php

namespace App\Controller\Api\Supply;

use App\Controller\MainController;
use App\Entity\Product;
use App\Repository\ProductRepository;
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
        return $this->json($products, Response::HTTP_OK, [],["groups" => "productWithRelation"]);

    }

    //! GET PRODUCT

    #[Route('/{id}', name: 'app_api_product_getProduct', methods: 'GET')]
    public function getProduct(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["error" => "The product with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($product, Response::HTTP_OK, [],["groups" => "productWithRelation"]);
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

        );
    }

    //! PUT PRODUCT

    #[Route('/{id}', name: 'app_api_supply_putProduct', methods: 'PUT')]
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

        return $this->json($updatedProduct, Response::HTTP_OK);
    }

    //! DELETE PRODUCT

    #[Route('/{id}', name: 'app_api_supply_deleteProduct', methods: 'DELETE')]
    public function deleteProduct(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["error" => "The product with ID " . $id . " does not exist"], Response::HTTP_BAD_REQUEST);
        }
        try {
            $productRepository->remove($product, true);

        } catch (ORMInvalidArgumentException $e) {

            return $this->json(["error" => "Failed to delete the product with ID " . $id . ""], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json("The product with ID " . $id . " has been deleted successfully", Response::HTTP_OK);
    }

}
