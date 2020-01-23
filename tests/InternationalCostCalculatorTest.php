<?php

namespace App\Tests;

use App\Entity\Orders;
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
            [Orders::PRODUCT_ID => 1, Orders::PRODUCT_QUANTITY => 10], // id 1 is a dummy product created when creating ProductsTestRepository
            [Orders::PRODUCT_ID => 1, Orders::PRODUCT_QUANTITY => 1]
        ];

        $repository = new ProductsTestRepository(new UserIdValidator(new UsersTestRepository()));
        $costCalculator = new InternationalCostCalculator($repository);
        $cost = $costCalculator->calculate($id_user, $line_items);
        $this->assertIsArray($cost);
        $this->assertArrayHasKey(Orders::ORDER_PRODUCTION_COST, $cost);
        $this->assertArrayHasKey(Orders::ORDER_SHIPPING_COST, $cost);
        $this->assertArrayHasKey(Orders::ORDER_TOTAL_COST, $cost);
        $this->assertEquals($cost[Orders::ORDER_PRODUCTION_COST], 11000);
        $this->assertEquals($cost[Orders::ORDER_SHIPPING_COST], 1950);
        $this->assertEquals($cost[Orders::ORDER_TOTAL_COST], 12950);
    }
}
