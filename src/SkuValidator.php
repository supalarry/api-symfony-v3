<?php


namespace App;


use App\Entity\Products;
use App\Interfaces\IProductsRepository;

class SkuValidator
{
    private $repository;

    public function __construct(IProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate(string $sku, int $user_id): bool
    {
        if ($this->repository->findOneBy([Products::PRODUCT_SKU => $sku, Products::PRODUCT_OWNER_ID => $user_id]))
            return (false);
        return (true);
    }
}