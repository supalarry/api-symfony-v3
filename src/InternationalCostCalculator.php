<?php


namespace App;


use App\Interfaces\IProductsRepository;

class InternationalCostCalculator
{
    const FIRST_MUG_SHIPPING = 500;
    const CONSECUTIVE_MUG_SHIPPING = 250;
    const FIRST_T_SHIRT_SHIPPING = 300;
    const CONSECUTIVE_T_SHIRT_SHIPPING = 150;

    private $repository;

    public function __construct(IProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function calculate(int $id_user, array $line_items): array
    {
        $production_cost = 0;
        $shipping_cost = 0;

        foreach ($line_items as $line_item)
        {
            $id = $line_item["id"];
            $quantity = $line_item["quantity"];

            $product = $this->repository->getById($id_user, $id);

            if ($product->getType() === "mug")
            {
                $production_cost += $product->getCost();
                $shipping_cost += self::FIRST_MUG_SHIPPING;
                $quantity--;
                $production_cost += $product->getCost() * $quantity;
                $shipping_cost += self::CONSECUTIVE_MUG_SHIPPING * $quantity;
            }
            elseif ($product->getType() === "t-shirt")
            {
                $production_cost += $product->getCost();
                $shipping_cost += self::FIRST_T_SHIRT_SHIPPING;
                $quantity--;
                $production_cost += $product->getCost() * $quantity;
                $shipping_cost += self::CONSECUTIVE_T_SHIRT_SHIPPING * $quantity;
            }
        }

        return [
            "production_cost" => $production_cost,
            "shipping_cost" => $shipping_cost,
            "total_cost" => $production_cost + $shipping_cost
        ];
    }
}