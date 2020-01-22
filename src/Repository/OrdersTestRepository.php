<?php


namespace App\Repository;


use App\Entity\Orders;
use App\Entity\Products;
use App\Interfaces\IOrdersProductsRelationRepository;
use App\Interfaces\IOrdersRepository;
use App\UserIdValidator;

class OrdersTestRepository implements IOrdersRepository
{
    private $db;
    private $id;
    private $userIdValidator;
    private $relationRepository;

    public function __construct(UserIdValidator $userIdValidator, IOrdersProductsRelationRepository $relationRepository)
    {
        $this->db = [];
        $this->id = 1;
        $this->userIdValidator = $userIdValidator;
        $this->relationRepository = $relationRepository;

        $characteristics = [
            "ship_to_address" => [
                "name" => "John",
                "surname" => "Doe",
                "street" => "Palm street 25-7",
                "state" => "California",
                "zip" => "60744",
                "country" => "US",
                "phone" => "+1 123 123 123"
            ],
            "line_items" => [
                ["id" => 1, "quantity" => 10],
                ["id" => 1, "quantity" => 1]
            ]
        ];
        $costs = [
            "production_cost" => 11000,
            "shipping_cost" => 650,
            "total_cost" => 11650
        ];

        $this->create($characteristics, $costs);
    }

    public function create(array $characteristics, array $costs)
    {
        $newOrder = new Orders();
        $newOrder->setId($this->id);
        $newOrder->setName($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_NAME]);
        $newOrder->setSurname($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_SURNAME]);
        $newOrder->setStreet($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STREET]);

        if (array_key_exists(Orders::ORDER_STATE, $characteristics[Orders::ORDER_SHIPPING_DATA]))
            $newOrder->setState($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STATE]);
        else
            $newOrder->setState(null);

        if (array_key_exists(Orders::ORDER_ZIP, $characteristics[Orders::ORDER_SHIPPING_DATA]))
            $newOrder->setZip($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_ZIP]);
        else
            $newOrder->setZip(null);

        $newOrder->setCountry($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_COUNTRY]);
        $newOrder->setPhone($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_PHONE]);
        $newOrder->setProductionCost($costs[Orders::ORDER_PRODUCTION_COST]);
        $newOrder->setShippingCost($costs[Orders::ORDER_SHIPPING_COST]);
        $newOrder->setTotalCost($costs[Orders::ORDER_TOTAL_COST]);
        $this->db[$this->id] = $newOrder;
        $this->id++;
        return $newOrder;
    }

    private function getEntityById(int $id)
    {
        foreach ($this->db as $order)
        {
            if ($order->getId() === $id)
                return ($order);
        }
        return (null);
    }

    public function getById(int $id_user, int $id)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            $order = [];
            $orderEntity = $this->getEntityById($id);
            if (!$orderEntity)
                return (null);

            $order["ship_to_address"] = array();
            $order["ship_to_address"]["name"] = $orderEntity->getName();
            $order["ship_to_address"]["surname"] = $orderEntity->getSurname();
            $order["ship_to_address"]["street"] = $orderEntity->getStreet();
            if ($orderEntity->getState())
                $order["ship_to_address"]["state"] = $orderEntity->getState();
            if ($orderEntity->getZip())
                $order["ship_to_address"]["zip"] = $orderEntity->getZip();
            $order["ship_to_address"]["country"] = $orderEntity->getCountry();
            $order["ship_to_address"]["phone"] = $orderEntity->getPhone();

            $order[Orders::ORDER_LINE_ITEMS] = $this->relationRepository->line_items($id_user, $id);

            $order["order_info"] = array();
            $order["order_info"]["id"] = $orderEntity->getId();
            $order["order_info"]["production_cost"] = $orderEntity->getProductionCost();
            $order["order_info"]["shipping_cost"] = $orderEntity->getShippingCost();
            $order["order_info"]["total_cost"] = $orderEntity->getTotalCost();
            return ($order);
        }
        return (null);
    }

    public function getAll(int $id_user)
    {
        $ordersEntities = $this->relationRepository->getUsersOrders($id_user);
        $orders = [];
        foreach ($ordersEntities as $order)
        {
            $orders[] = $this->getById($id_user, $order->getId());
        }
        return $orders;
    }
}