<?php

namespace App\Controller;

use App\CreateOrder;
use App\Exception\CreateOrderServiceException;
use App\Interfaces\IOrdersProductsRelationRepository;
use App\Interfaces\IOrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @Route("/users/{id_user}/orders", name="createOrder", methods={"POST"})
     * @param CreateOrder $createOrderService
     * @param int $id_user
     * @return JsonResponse
     */
    public function createOrder(CreateOrder $createOrderService, int $id_user)
    {
        try {
            $createdOrder = $createOrderService->handle($id_user);
            return $this->json($createdOrder, Response::HTTP_CREATED);
        } catch (CreateOrderServiceException $e){
            return $this->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{id_user}/orders/{id}", name="viewOrder", methods={"GET"})
     * @param IOrdersRepository $ordersRepository
     * @param int $id_user
     * @param int $id
     * @return Response
     */
    public function getOrderById(IOrdersRepository $ordersRepository, int $id_user, int $id)
    {
        $order = $ordersRepository->getById($id_user, $id);
        if ($order)
            return $this->json($order, Response::HTTP_OK);
        return new Response(null,Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/users/{id_user}/orders", name="viewOrders", methods={"GET"})
     * @param IOrdersRepository $ordersRepository
     * @param int $id_user
     * @return Response
     */
    public function getOrders(IOrdersRepository $ordersRepository, int $id_user)
    {
        $orders = $ordersRepository->getAll($id_user);
        if ($orders)
            return $this->json($orders, Response::HTTP_OK);
        return new Response(null,Response::HTTP_NOT_FOUND);
    }
}
