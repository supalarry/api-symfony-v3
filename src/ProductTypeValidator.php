<?php


namespace App;


class ProductTypeValidator
{
    const MUG = "mug";
    const TSHIRT = "t-shirt";

    private $allowedProducts;

    public function __construct()
    {
        $this->allowedProducts = [
            self::MUG,
            self::TSHIRT
        ];
    }

    public function validate(string $product): bool
    {
        if (in_array($product, $this->allowedProducts))
            return (true);
        return (false);
    }

    public function getAllowed(): string
    {
        $productsString = '';
        foreach ($this->allowedProducts as $product)
        {
            if ($productsString === '')
                $productsString = $productsString . $product;
            else
                $productsString = $productsString . " " . $product;
        }
        return $productsString;
    }
}