<?php

namespace App\Tests;

use App\Entity\Products;
use App\ErrorsLoader;
use App\Exception\ValidateProductException;
use App\ProductTypeValidator;
use App\Repository\ProductsTestRepository;
use App\Repository\UsersTestRepository;
use App\SkuValidator;
use App\TitleValidator;
use App\UserIdValidator;
use App\ValidateProduct;
use PHPUnit\Framework\TestCase;

class ValidateProductTest extends TestCase
{
    public function test_valid_keys_and_values()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "wow",
            Products::PRODUCT_SKU => "100-abc-1000",
            Products::PRODUCT_COST => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        $validator->validateKeys();

        $errors = $validator->getErrors();

        $this->assertEmpty($errors);
    }

    public function test_type_key_not_set()
    {
        $json_body = [
            "placeholder" => "t-shirt",
            Products::PRODUCT_TITLE => "wow",
            Products::PRODUCT_SKU => "100-abc-1000",
            Products::PRODUCT_COST => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TYPE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TYPE][0], 'type key not set');
        }
    }

    public function test_title_key_not_set()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "t-shirt",
            "placeholder" => "wow",
            Products::PRODUCT_SKU => "100-abc-1000",
            Products::PRODUCT_COST => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TITLE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TITLE][0], 'title key not set');
        }
    }

    public function test_sku_key_not_set()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "wow",
            "placeholder" => "100-abc-1000",
            Products::PRODUCT_COST => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_SKU, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_SKU][0], 'sku key not set');
        }
    }

    public function test_cost_key_not_set()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "wow",
            Products::PRODUCT_SKU => "100-abc-1000",
            "placeholder" => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_COST, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_COST][0], 'cost key not set');
        }
    }

    public function test_invalid_type()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "whatever",
            Products::PRODUCT_TITLE => "%%%%%wow%%%%%",
            Products::PRODUCT_SKU => "100-abc-100",
            Products::PRODUCT_COST => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $productTypeValidator = new ProductTypeValidator();
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TYPE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TYPE][0], 'Invalid type. Allowed types: ' . $productTypeValidator->getAllowed());
        }
    }

    public function test_invalid_title()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "%%%%%wow%%%%%",
            Products::PRODUCT_SKU => "100-abc-100",
            Products::PRODUCT_COST => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TITLE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TITLE][0], 'Invalid title. It can only consist of letters, digits and dash(-)');
        }
    }

    public function test_taken_sku()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "wow",
            Products::PRODUCT_SKU => "100-abc-999",
            Products::PRODUCT_COST => 1000,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_SKU, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_SKU][0], 'Invalid SKU. It must be unique, and it appears another product already has it');
        }
    }

    public function test_cost_is_float()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "t-shirt",
            Products::PRODUCT_TITLE => "wow",
            Products::PRODUCT_SKU => "100-abc-1000",
            Products::PRODUCT_COST => 100.00,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_COST, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_COST][0], 'Invalid cost. It must be an integer describing price with smallest money unit');
        }
    }

    public function test_no_keys()
    {
        $json_body = [

        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TYPE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TYPE][0], 'type key not set');
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TITLE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TITLE][0], 'title key not set');
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_SKU, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_SKU][0], 'sku key not set');
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_COST, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_COST][0], 'cost key not set');
        }
    }

    public function test_all_keys_have_error()
    {
        $json_body = [
            Products::PRODUCT_TYPE => "whatever",
            Products::PRODUCT_TITLE => "wow******",
            Products::PRODUCT_SKU => "100-abc-999",
            Products::PRODUCT_COST => 100.00,
        ];

        $errors = [];

        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $repository = new ProductsTestRepository($userIdValidator);

        $validator = new ValidateProduct($json_body, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($repository), new ErrorsLoader());

        try {
            $validator->validateKeys();
        } catch (ValidateProductException $e){
            $errors = $validator->getErrors();
            $productTypeValidator = new ProductTypeValidator();
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TYPE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TYPE][0], 'Invalid type. Allowed types: ' . $productTypeValidator->getAllowed());
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_TITLE, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_TITLE][0], 'Invalid title. It can only consist of letters, digits and dash(-)');
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_SKU, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_SKU][0], 'Invalid SKU. It must be unique, and it appears another product already has it');
            $this->assertArrayHasKey(PRODUCTS::PRODUCT_COST, $errors);
            $this->assertEquals($errors[PRODUCTS::PRODUCT_COST][0], 'Invalid cost. It must be an integer describing price with smallest money unit');
        }
    }
}
