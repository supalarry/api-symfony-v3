<?php

namespace App\Tests;

use App\AddressValidator;
use App\AlphabeticStringValidator;
use App\CountryValidator;
use App\DomesticAddressValidator;
use App\ErrorsLoader;
use App\Exception\OrderValidatorException;
use App\InternationalAddressValidator;
use App\LineItemsValidator;
use App\OrderValidator;
use App\PhoneValidator;
use App\Repository\ProductsTestRepository;
use App\Repository\UsersTestRepository;
use App\ShipmentType;
use App\StateValidator;
use App\StreetValidator;
use App\UserIdValidator;
use App\ZipCodeValidator;
use PHPUnit\Framework\TestCase;

class OrderValidatorTest extends TestCase
{
    private $addressValidator;
    private $lineItemsValidator;

    public function __construct()
    {
        parent::__construct();
        /* creating necessary objects to create order validator*/
        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());
        $internationalAddressValidator = new InternationalAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());
        $this->addressValidator = new AddressValidator(new ShipmentType(), $domesticAddressValidator, $internationalAddressValidator);

        $userValidator = new UserIdValidator(new UsersTestRepository());
        $this->lineItemsValidator = new LineItemsValidator(new ProductsTestRepository($userValidator), new ErrorsLoader());
    }

    public function test_valid_order()
    {
        $id_user = 1;

        $order = [
            "ship_to_address" => [
                "name" => "John",
                "surname" => "Doe",
                "street" => "Palm street 25-7",
                "state" => "California",
                "zip" => "60744",
                "country" => "US",
                "phone" => "+1 123 123 123"
            ],
            "line_items" => [
                ["id" => 1, "quantity" => 10],
                ["id" => 1, "quantity" => 1]
            ]
        ];

        $orderValidator = new OrderValidator($this->addressValidator, $this->lineItemsValidator);
        $orderValidator->validate(1, $order);
        $errors = $orderValidator->getErrors();
        $this->assertEmpty($errors);
    }

    public function test_missing_ship_to_address_key()
    {
        $id_user = 1;

        $order = [
            "XXXX_XX_XXXXXXX" => [
                "name" => "John",
                "surname" => "Doe",
                "street" => "Palm street 25-7",
                "state" => "California",
                "zip" => "60744",
                "country" => "US",
                "phone" => "+1 123 123 123"
            ],
            "line_items" => [
                ["id" => 1, "quantity" => 10],
                ["id" => 1, "quantity" => 1]
            ]
        ];

        $orderValidator = new OrderValidator($this->addressValidator, $this->lineItemsValidator);
        try {
            $orderValidator->validate(1, $order);
        } catch (OrderValidatorException $e) {
            $errors = $e->getErrors();
            $this->assertIsArray($errors);
            $this->assertArrayHasKey("ship_to_address", $errors);
            $this->assertEquals($errors["ship_to_address"], "shipping address not set");
        }
    }

    public function test_missing_line_items_key()
    {
        $id_user = 1;

        $order = [
            "ship_to_address" => [
                "name" => "John",
                "surname" => "Doe",
                "street" => "Palm street 25-7",
                "state" => "California",
                "zip" => "60744",
                "country" => "US",
                "phone" => "+1 123 123 123"
            ],
            "XXXX_XXXXX" => [
                ["id" => 1, "quantity" => 10],
                ["id" => 1, "quantity" => 1]
            ]
        ];

        $orderValidator = new OrderValidator($this->addressValidator, $this->lineItemsValidator);
        try {
            $orderValidator->validate(1, $order);
        } catch (OrderValidatorException $e) {
            $errors = $e->getErrors();
            $this->assertIsArray($errors);
            $this->assertArrayHasKey("line_items", $errors);
            $this->assertEquals($errors["line_items"], "order does not contain any products");
        }
    }

    public function test_invalid_country_within_ship_to_address()
    {
        $id_user = 1;

        $order = [
            "ship_to_address" => [
                "name" => "John",
                "surname" => "Doe",
                "street" => "Palm street 25-7",
                "state" => "California",
                "zip" => "60744",
                "country" => "XXXXXXXXXXXXX",
                "phone" => "+1 123 123 123"
            ],
            "line_items" => [
                ["id" => 1, "quantity" => 10],
                ["id" => 1, "quantity" => 1]
            ]
        ];

        $orderValidator = new OrderValidator($this->addressValidator, $this->lineItemsValidator);
        try {
            $orderValidator->validate(1, $order);
        } catch (OrderValidatorException $e) {
            $errors = $e->getErrors();
            $this->assertIsArray($errors);
            $this->assertArrayHasKey("ship_to_address", $errors);
            $this->assertEquals($errors["ship_to_address"]["country"][0], "invalid country");
        }
    }

    public function test_invalid_line_item_id_within()
    {
        $id_user = 1;

        $order = [
            "ship_to_address" => [
                "name" => "John",
                "surname" => "Doe",
                "street" => "Palm street 25-7",
                "state" => "California",
                "zip" => "60744",
                "country" => "US",
                "phone" => "+1 123 123 123"
            ],
            "line_items" => [
                ["id" => 100, "quantity" => 10],
                ["id" => 1, "quantity" => 1]
            ]
        ];

        $orderValidator = new OrderValidator($this->addressValidator, $this->lineItemsValidator);
        try {
            $orderValidator->validate(1, $order);
        } catch (OrderValidatorException $e) {
            $errors = $e->getErrors();
            $this->assertIsArray($errors);
            $this->assertArrayHasKey("line_items", $errors);
            $this->assertEquals($errors["line_items"]["id"][0], "invalid product id for user with id of 1 for line item number 1");
        }
    }
}
