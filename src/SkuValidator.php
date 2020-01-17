<?php


namespace App;


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
        if ($this->repository->findOneBy(["sku" => $sku]))
            return (false);
        return (true);
    }
}