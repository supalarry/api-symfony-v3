<?php


namespace App\Repository\Test;

use App\Entity\Order;
use App\Entity\Relation;
use App\Entity\Product;
use App\Interfaces\IRelationRepo;
use App\Interfaces\IProductRepo;
use App\Validators\UserValidators\UidValidator;

class RelationTestRepo implements IRelationRepo
{
    private $db;
    private $id;
    private $userIdValidator;
    private $productsRepository;

    public function __construct(UidValidator $userIdValidator, IProductRepo $productsRepository)
    {
        $this->db = [];
        $this->id = 1;
        $this->userIdValidator = $userIdValidator;
        $this->productsRepository = $productsRepository;
        $this->create(1, [[Order::PRODUCT_ID => 1, Order::PRODUCT_QUANTITY => 10], [Order::PRODUCT_ID => 1, Order::PRODUCT_QUANTITY => 1]], 1);
    }

    public function create(int $order_id, array $line_items, int $id_user)
    {
        foreach ($line_items as $item)
        {
            $relation = new Relation();
            $relation->setOwnerId($id_user);
            $relation->setId($this->id);
            $relation->setOrderId($order_id);
            $relation->setProductId($item[Order::PRODUCT_ID]);
            $relation->setQuantity($item[Order::PRODUCT_QUANTITY]);
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
            $item[Order::PRODUCT_ID] = $relation_product->getProductId();
            $item[Order::PRODUCT_QUANTITY] = $relation_product->getQuantity();
            $product = $this->productsRepository->getById($id_user, $item[Order::PRODUCT_ID]);
            $item[Product::OWNER_ID] = $product->getOwnerId();
            $item[Product::TYPE] = $product->getType();
            $item[Product::TITLE] = $product->getTitle();
            $item[Product::SKU] = $product->getSku();
            $item[Product::COST] = $product->getCost();
            $item[Product::TOTAL_COST] = $item[Product::COST] * $item[Order::PRODUCT_QUANTITY];
            $line_items[] = $item;
        }
        return ($line_items);
    }
}