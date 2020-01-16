<?php

namespace App\Tests;

use App\Repository\ProductsTestRepository;
use App\SkuValidator;
use PHPUnit\Framework\TestCase;

class SkuValidatorTest extends TestCase
{
    public function test_available_sku()
    {
        $skuValidator = new SkuValidator(new ProductsTestRepository());
        $valid = $skuValidator->validate("100-abc-1000");
        $this->assertEquals($valid, true);
    }

    public function test_unavailable_sku()
    {
        $skuValidator = new SkuValidator(new ProductsTestRepository());
        $valid = $skuValidator->validate("100-abc-999");
        $this->assertEquals($valid, false);
    }
}
