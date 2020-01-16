<?php


namespace App;


use App\Interfaces\IRepository;

class SkuValidator
{
    private $repository;

    public function __construct(IRepository $repository)
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