<?php


namespace App\Repository;


use App\Entity\Products;
use App\Entity\Users;
use App\Interfaces\IEntity;
use App\Interfaces\IRepository;

class ProductsTestRepository implements IRepository
{
    private $db;
    private $id;

    public function __construct()
    {
        $this->db = [];
        $this->id = 1;
        $this->create([
            Products::PRODUCT_OWNER_ID => 1,
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "much shirt, such style!",
            Products::PRODUCT_SKU => "100-abc-999",
            Products::PRODUCT_COST => 1000
        ]);
    }

    public function create(array $characteristics): IEntity
    {
        $newProduct = new Products();
        $newProduct->setId($this->id);
        $newProduct->setOwnerId($characteristics[Products::PRODUCT_OWNER_ID]);
        $newProduct->setType($characteristics[Products::PRODUCT_TYPE]);
        $newProduct->setTitle($characteristics[Products::PRODUCT_TITLE]);
        $newProduct->setSku($characteristics[Products::PRODUCT_SKU]);
        $newProduct->setCost($characteristics[Products::PRODUCT_COST]);
        $this->db[$this->id] = $newProduct;
        $this->id++;
        return $newProduct;
    }

    public function getById(int $id)
    {
        if (array_key_exists($id, $this->db))
            return $this->db[$id];
        return null;
    }

    public function getAll(): array
    {
        return $this->db;
    }

    public function findOneBy(array $characteristics)
    {
        if ($characteristics["sku"] === "100-abc-999")
            return (true);
        return (null);
    }
}