<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\OrdersProductsRelation;
use App\Entity\Products;
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
            $relation->setProductId($item[Orders::PRODUCT_ID]);
            $relation->setQuantity($item[Orders::PRODUCT_QUANTITY]);
            $this->em->persist($relation);
            $this->em->flush();
        }
    }

    public function getOrderProducts($id_user, int $id)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            return $this->findBy([
                OrdersProductsRelation::ORDER_ID => $id
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
            $item[Orders::PRODUCT_ID] = $relation_product->getProductId();
            $item[Orders::PRODUCT_QUANTITY] = $relation_product->getQuantity();
            $product = $this->productsRepository->getById($id_user, $item[Orders::PRODUCT_ID]);
            $item[Products::PRODUCT_OWNER_ID] = $product->getOwnerId();
            $item[Products::PRODUCT_TYPE] = $product->getType();
            $item[Products::PRODUCT_TITLE] = $product->getTitle();
            $item[Products::PRODUCT_SKU] = $product->getSku();
            $item[Products::PRODUCT_COST] = $product->getCost();
            $item[Products::PRODUCT_TOTAL_COST] = $item[Products::PRODUCT_COST] * $item[Orders::PRODUCT_QUANTITY];
            $line_items[] = $item;
        }
        return ($line_items);
    }
}
