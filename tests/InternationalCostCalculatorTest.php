<?php

namespace App\Tests;

use App\InternationalCostCalculator;
use App\Repository\ProductsTestRepository;
use App\Repository\UsersTestRepository;
use App\UserIdValidator;
use PHPUnit\Framework\TestCase;

class InternationalCostCalculatorTest extends TestCase
{
    public function test_calculate_international_cost()
    {
        $id_user = 1;

        $line_items = [
            ["id" => 1, "quantity" => 10], // id 1 is a dummy product created when creating ProductsTestRepository
            ["id" => 1, "quantity" => 1]
        ];

        $repository = new ProductsTestRepository(new UserIdValidator(new UsersTestRepository()));
        $costCalculator = new InternationalCostCalculator($repository);
        $cost = $costCalculator->calculate($id_user, $line_items);
        $this->assertIsArray($cost);
        $this->assertArrayHasKey("production_cost", $cost);
        $this->assertArrayHasKey("shipping_cost", $cost);
        $this->assertArrayHasKey("total_cost", $cost);
        $this->assertEquals($cost["production_cost"], 11000);
        $this->assertEquals($cost["shipping_cost"], 1950);
        $this->assertEquals($cost["total_cost"], 12950);
    }
}
