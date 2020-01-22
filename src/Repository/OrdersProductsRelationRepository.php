<?php

namespace App\Repository;

use App\Entity\OrdersProductsRelation;
use App\Interfaces\IOrdersProductsRelationRepository;
use App\Interfaces\IProductsRepository;
use App\UserIdValidator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method OrdersProductsRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersProductsRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersProductsRelation[]    findAll()
 * @method OrdersProductsRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersProductsRelationRepository extends ServiceEntityRepository implements IOrdersProductsRelationRepository
{
    private $em;
    private $productsRepository;
    private $userIdValidator;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, IProductsRepository $productsRepository, UserIdValidator $userIdValidator)
    {
        parent::__construct($registry, OrdersProductsRelation::class);
        $this->em = $em;
        $this->productsRepository = $productsRepository;
        $this->userIdValidator = $userIdValidator;
    }

    public function create(int $order_id, array $line_items)
    {
        foreach ($line_items as $item)
        {
            $relation = new OrdersProductsRelation();
            $relation->setOrderId($order_id);
            $relation->setProductId($item["id"]);
            $relation->setQuantity($item["quantity"]);
            $this->em->persist($relation);
            $this->em->flush();
        }
    }

    public function getOrderProducts($id_user, int $id)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            return $this->findBy([
                "order_id" => $id
            ]);
        }
        return (null);
    }

    public function line_items(int $id_user, int $id)
    {
        $relation_products = $this->getOrderProducts($id_user, $id);
        $line_items = [];
        foreach ($relation_products as $relation_product)
        {
            $item = [];
            $item["id"] = $relation_product->getProductId();
            $item["quantity"] = $relation_product->getQuantity();
            $product = $this->productsRepository->getById($id_user, $item["id"]);
            $item["owner_id"] = $product->getOwnerId();
            $item["type"] = $product->getType();
            $item["title"] = $product->getTitle();
            $item["sku"] = $product->getSku();
            $item["cost"] = $product->getCost();
            $item["totalCost"] = $item["cost"] * $item["quantity"];
            $line_items[] = $item;
        }
        return ($line_items);
    }

    public function getUsersOrders($id_user)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            return $this->productsRepository->findBy([
                "owner_id" => $id_user
            ]);
        }
        return (null);
    }
}
