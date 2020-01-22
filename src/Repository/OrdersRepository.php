<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Interfaces\IEntity;
use App\Interfaces\IOrdersProductsRelationRepository;
use App\UserIdValidator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Interfaces\IOrdersRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository implements IOrdersRepository
{
    private $em;
    private $userIdValidator;
    private $relationRepository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, UserIdValidator $userIdValidator, IOrdersProductsRelationRepository $relationRepository)
    {
        parent::__construct($registry, Orders::class);
        $this->em = $em;
        $this->userIdValidator = $userIdValidator;
        $this->relationRepository = $relationRepository;
    }

    public function create(array $characteristics, array $costs): IEntity
    {
        $newOrder = new Orders();
        $newOrder->setName($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_NAME]);
        $newOrder->setSurname($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_OWNER_SURNAME]);
        $newOrder->setStreet($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STREET]);

        if (array_key_exists(Orders::ORDER_STATE, $characteristics))
            $newOrder->setState($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_STATE]);
        else
            $newOrder->setState(null);

        if (array_key_exists(Orders::ORDER_ZIP, $characteristics))
            $newOrder->setZip($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_ZIP]);
        else
            $newOrder->setZip(null);

        $newOrder->setCountry($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_COUNTRY]);
        $newOrder->setPhone($characteristics[Orders::ORDER_SHIPPING_DATA][Orders::ORDER_PHONE]);
        $newOrder->setProductionCost($costs[Orders::ORDER_PRODUCTION_COST]);
        $newOrder->setShippingCost($costs[Orders::ORDER_SHIPPING_COST]);
        $newOrder->setTotalCost($costs[Orders::ORDER_TOTAL_COST]);
        $this->em->persist($newOrder);
        $this->em->flush();
        return $newOrder;
    }

    private function getEntityById(int $id)
    {
        return $this->findOneBy([
            "id" => $id,
        ]);
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
