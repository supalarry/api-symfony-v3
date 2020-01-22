<?php

namespace App\Controller;

use App\CreateProduct;
use App\Exception\CreateProductServiceException;
use App\Interfaces\IProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/users/{id_user}/products", name="createProduct", methods={"POST"})
     * @param CreateProduct $createProductService
     * @param int $id_user
     * @return JsonResponse
     */
    public function createProduct(CreateProduct $createProductService, int $id_user)
    {
        try {
            $createdProduct = $createProductService->handle($id_user);
            return $this->json($createdProduct, Response::HTTP_CREATED);
        } catch (CreateProductServiceException $e){
            return $this->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{id_user}/products/{id}", name="getProduct", methods={"GET"})
     * @param IProductsRepository $repository
     * @param int $id_user
     * @param int $id
     * @return JsonResponse|Response
     */

    public function getProductById(IProductsRepository $repository, int $id_user, int $id): Response
    {
        $product = $repository->getById($id_user, $id);
        if ($product)
            return $this->json($product, Response::HTTP_OK);
        return new Response(null,Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/users/{id_user}/products", name="getProducts", methods={"GET"})
     * @param IProductsRepository $repository
     * @param int $id_user
     * @return JsonResponse|Response
     */

    public function getProducts(IProductsRepository $repository, int $id_user): Response
    {
        $products = $repository->getAll($id_user);
        if ($products)
            return $this->json($products, Response::HTTP_OK);
        return new Response(null,Response::HTTP_NOT_FOUND);
    }
}
