<?php


namespace App\Repository;


use App\Entity\OrdersProductsRelation;
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
        $this->create(1, [["id" => 1, "quantity" => 10], ["id" => 1, "quantity" => 1]]);
    }

    public function create(int $order_id, array $line_items)
    {
        foreach ($line_items as $item)
        {
            $relation = new OrdersProductsRelation();
            $relation->setId($this->id);
            $relation->setOrderId($order_id);
            $relation->setProductId($item["id"]);
            $relation->setQuantity($item["quantity"]);
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
                if ($product->getOrderId() === $id)
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
            $relation_products = [];
            foreach ($this->productsRepository->db as $product)
            {
                if ($product->getOwnerId() === $id_user)
                    $relation_products[] = $product;
            }
            if (empty($relation_products))
                return (null);
            return $relation_products;
        }
        return (null);
    }
}