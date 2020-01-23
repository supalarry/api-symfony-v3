<?php


namespace App\Repository;


use App\Entity\Orders;
use App\Entity\OrdersProductsRelation;
use App\Entity\Products;
use App\Interfaces\IOrdersProductsRelationRepository;
use App\Interfaces\IProductsRepository;
use App\UserIdValidator;

class OrdersProductsRelationTestRepository implements IOrdersProductsRelationRepository
{
    private $db;
    private $id;
    private $userIdValidator;
    private $productsRepository;

    public function __construct(UserIdValidator $userIdValidator, IProductsRepository $productsRepository)
    {
        $this->db = [];
        $this->id = 1;
        $this->userIdValidator = $userIdValidator;
        $this->productsRepository = $productsRepository;
        $this->create(1, [[Orders::PRODUCT_ID => 1, Orders::PRODUCT_QUANTITY => 10], [Orders::PRODUCT_ID => 1, Orders::PRODUCT_QUANTITY => 1]], 1);
    }

    public function create(int $order_id, array $line_items, int $id_user)
    {
        foreach ($line_items as $item)
        {
            $relation = new OrdersProductsRelation();
            $relation->setOwnerId($id_user);
            $relation->setId($this->id);
            $relation->setOrderId($order_id);
            $relation->setProductId($item[Orders::PRODUCT_ID]);
            $relation->setQuantity($item[Orders::PRODUCT_QUANTITY]);
            $this->db[$this->id] = $relation;
            $this->id++;
        }
    }

    public function getOrderProducts($id_user, int $id)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            $relation_products = [];
            foreach ($this->db as $product)
            {
                if ($product->getOrderId() === $id && $product->getOwnerId() === $id_user)
                    $relation_products[] = $product;
            }
            if (empty($relation_products))
                return (null);
            return $relation_products;
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