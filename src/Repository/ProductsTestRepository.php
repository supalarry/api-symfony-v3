<?php


namespace App\Repository;


use App\Entity\Products;
use App\Entity\Users;
use App\Interfaces\IEntity;
use App\Interfaces\IProductsRepository;
use App\UserIdValidator;

class ProductsTestRepository implements IProductsRepository
{
    public $db;
    private $id;
    private $userIdValidator;

    public function __construct(UserIdValidator $userIdValidator)
    {
        $this->db = [];
        $this->id = 1;
        $this->userIdValidator = $userIdValidator;
        $this->create(1, [
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "much shirt, such style!",
            Products::PRODUCT_SKU => "100-abc-999",
            Products::PRODUCT_COST => 1000
        ]);
    }

    public function create(int $id_owner, array $characteristics): IEntity
    {
        $newProduct = new Products();
        $newProduct->setId($this->id);
        $newProduct->setOwnerId($id_owner);
        $newProduct->setType($characteristics[Products::PRODUCT_TYPE]);
        $newProduct->setTitle($characteristics[Products::PRODUCT_TITLE]);
        $newProduct->setSku($characteristics[Products::PRODUCT_SKU]);
        $newProduct->setCost($characteristics[Products::PRODUCT_COST]);
        $this->db[$this->id] = $newProduct;
        $this->id++;
        return $newProduct;
    }

    public function getById(int $id_user, int $id)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            if (array_key_exists($id, $this->db) && $this->db[$id]->getOwnerId() === $id_user)
            {
                return $this->db[$id];
            }
        }
        return null;
    }

    public function getAll(int $id_user)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            $products = [];
            foreach ($this->db as $product)
            {
                if ($product->getOwnerId() === $id_user)
                    $products[] = $product;
            }
            return $products;
        }
        return (null);
    }

    public function findOneBy(array $characteristics)
    {
        $characteristicsCount = count ($characteristics);
        $characteristicsFound = 0;
        foreach ($this->db as $product)
        {
            foreach ($characteristics as $key => $value)
            {
                if ($key === Products::PRODUCT_ID && $product->getId() === $value)
                    $characteristicsFound++;
                elseif($key === Products::PRODUCT_OWNER_ID && $product->getOwnerId() === $value)
                    $characteristicsFound++;
                elseif($key === Products::PRODUCT_TYPE && $product->getType() === $value)
                    $characteristicsFound++;
                elseif($key === Products::PRODUCT_TITLE && $product->getTitle() === $value)
                    $characteristicsFound++;
                elseif($key === Products::PRODUCT_SKU && $product->getSku() === $value)
                    $characteristicsFound++;
                elseif($key === Products::PRODUCT_COST && $product->getCost() === $value)
                    $characteristicsFound++;
            }
            if ($characteristicsFound === $characteristicsCount)
                return (true);
        }
        return (null);
    }
}