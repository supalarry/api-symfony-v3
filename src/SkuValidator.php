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

    public function validate(string $sku): bool
    {
        if ($this->repository->findOneBy([Products::PRODUCT_SKU => $sku]))
            return (false);
        return (true);
    }
}