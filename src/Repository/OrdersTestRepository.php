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
            "shipToAddress" => [
                "name" => "John",
                "surname" => "Doe",
                "street" => "Palm street 25-7",
                "state" => "California",
                "zip" => "60744",
                "country" => "US",
                "phone" => "+1 123 123 123"
            ],
            "lineItems" => [
                ["id" => 1, "quantity" => 10],
                ["id" => 1, "quantity" => 1]
            ]
        ];
        $costs = [
            "productionCost" => 11000,
            "shippingCost" => 650,
            "totalCost" => 11650
        ];

        $this->create($characteristics, $costs, 1);
    }

    public function create(array $characteristics, array $costs, int $id_user)
    {
        $newOrder = new Orders();
        $newOrder->setId($this->id);
        $newOrder->setOwnerId($id_user);
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

            $order[Orders::ORDER_SHIPPING_DATA] = array();
            $order[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_NAME] = $orderEntity->getName();
            $order[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_SURNAME] = $orderEntity->getSurname();
            $order[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STREET] = $orderEntity->getStreet();
            if ($orderEntity->getState())
                $order[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STATE] = $orderEntity->getState();
            if ($orderEntity->getZip())
                $order[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_ZIP] = $orderEntity->getZip();
            $order[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_COUNTRY] = $orderEntity->getCountry();
            $order[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_PHONE] = $orderEntity->getPhone();

            $order[Orders::ORDER_LINE_ITEMS] = $this->relationRepository->line_items($id_user, $id);

            $order[Orders::ORDER_INFO] = array();
            $order[Orders::ORDER_INFO][Orders::ORDER_ID] = $orderEntity->getId();
            $order[Orders::ORDER_INFO][Orders::ORDER_PRODUCTION_COST] = $orderEntity->getProductionCost();
            $order[Orders::ORDER_INFO][Orders::ORDER_SHIPPING_COST] = $orderEntity->getShippingCost();
            $order[Orders::ORDER_INFO][Orders::ORDER_TOTAL_COST] = $orderEntity->getTotalCost();
            return ($order);
        }
        return (null);
    }

    public function getAll(int $id_user)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            $ordersEntities = [];
            foreach ($this->db as $order)
            {
                if ($order->getOwnerId() === $id_user)
                    $ordersEntities[] = $order;
            }
            $orders = [];
            foreach ($ordersEntities as $order)
            {
                $orders[] = $this->getById($id_user, $order->getId());
            }
            return $orders;
        }
        return (null);
    }
}