<?php

namespace App\Tests;

use App\Repository\ProductsTestRepository;
use App\Repository\UsersTestRepository;
use App\SkuValidator;
use App\UserIdValidator;
use PHPUnit\Framework\TestCase;

class SkuValidatorTest extends TestCase
{
    public function test_available_sku()
    {
        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $skuValidator = new SkuValidator(new ProductsTestRepository($userIdValidator));
        $valid = $skuValidator->validate("100-abc-1000");
        $this->assertEquals($valid, true);
    }

    public function test_unavailable_sku()
    {
        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $skuValidator = new SkuValidator(new ProductsTestRepository($userIdValidator));
        $valid = $skuValidator->validate("100-abc-999");
        $this->assertEquals($valid, false);
    }
}
