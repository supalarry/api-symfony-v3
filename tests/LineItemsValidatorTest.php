<?php

namespace App\Tests;

use App\ErrorsLoader;
use App\LineItemsValidator;
use App\Repository\ProductsTestRepository;
use App\Repository\UsersTestRepository;
use App\UserIdValidator;
use PHPUnit\Framework\TestCase;

class LineItemsValidatorTest extends TestCase
{
    public function test_valid_line_item()
    {
        $items = [["id" => 1, "quantity" => 10]];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertTrue($valid);
    }

    public function test_valid_line_items()
    {
        $items = [["id" => 1, "quantity" => 10], ["id" => 1, "quantity" => 2]];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertTrue($valid);
    }

    public function test_no_product_id()
    {
        $items = [["quantity" => 10]];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertFalse($valid);

        $errors = $lineItemsValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey("id", $errors);
        $this->assertEquals($errors["id"][0], "id field not set for line item number 1");
        $this->assertArrayHasKey("line_items", $errors);
        $this->assertEquals($errors["line_items"][0], "order must contain at least 1 product with a valid id and quantity");
    }

    public function test_invalid_product_id()
    {
        $items = [["id" => 100, "quantity" => 10]];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertFalse($valid);

        $errors = $lineItemsValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey("id", $errors);
        $this->assertEquals($errors["id"][0], "invalid product id for user with id of 1 for line item number 1");
        $this->assertArrayHasKey("line_items", $errors);
        $this->assertEquals($errors["line_items"][0], "order must contain at least 1 product with a valid id and quantity");
    }

    public function test_no_product_quantity()
    {
        $items = [["id" => 1]];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertFalse($valid);

        $errors = $lineItemsValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey("quantity", $errors);
        $this->assertEquals($errors["quantity"][0], "quantity field not set for line item number 1");
        $this->assertArrayHasKey("line_items", $errors);
        $this->assertEquals($errors["line_items"][0], "order must contain at least 1 product with a valid id and quantity");
    }

    public function test_invalid_quantity()
    {
        $items = [["id" => 100, "quantity" => -1]];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertFalse($valid);

        $errors = $lineItemsValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey("quantity", $errors);
        $this->assertEquals($errors["quantity"][0], "quantity must be at least 1 for line item number 1");
        $this->assertArrayHasKey("line_items", $errors);
        $this->assertEquals($errors["line_items"][0], "order must contain at least 1 product with a valid id and quantity");
    }

    public function test_no_line_items()
    {
        $items = [];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertFalse($valid);

        $errors = $lineItemsValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey("line_items", $errors);
        $this->assertEquals($errors["line_items"][0], "order must contain at least 1 product with a valid id and quantity");
    }

    public function test_empty_line_item()
    {
        $items = [[]];

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());

        $valid = $lineItemsValidator->validate(1, $items);

        $this->assertFalse($valid);

        $errors = $lineItemsValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey("line_items", $errors);
        $this->assertEquals($errors["line_items"][0], "order must contain at least 1 product with a valid id and quantity");
    }



}
