<?php

namespace App\Tests;

use App\AlphabeticStringValidator;
use App\CountryValidator;
use App\DomesticAddressValidator;
use App\Entity\Orders;
use App\ErrorsLoader;
use App\PhoneValidator;
use App\StateValidator;
use App\StreetValidator;
use App\ZipCodeValidator;
use PHPUnit\Framework\TestCase;

class DomesticAddressValidatorTest extends TestCase
{
    public function test_valid_domestic_address()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "Palm street 25-7",
            "state" => "California",
            "zip" => "60744",
            "country" => "US",
            "phone" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertTrue($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertEmpty($errors);
    }

    public function test_name_not_set()
    {
        $ship_to_address = [
            "XXXX" => "John",
            "surname" => "Doe",
            "street" => "Palm street 25-7",
            "state" => "California",
            "zip" => "60744",
            "country" => "US",
            "phone" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertFalse($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey(Orders::ORDER_OWNER_NAME, $errors);
        $this->assertEquals($errors[Orders::ORDER_OWNER_NAME][0], "name key not set");
    }

    public function test_surname_not_set()
    {
        $ship_to_address = [
            "name" => "John",
            "xxxxxxx" => "Doe",
            "street" => "Palm street 25-7",
            "state" => "California",
            "zip" => "60744",
            "country" => "US",
            "phone" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertFalse($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey(Orders::ORDER_OWNER_SURNAME, $errors);
        $this->assertEquals($errors[Orders::ORDER_OWNER_SURNAME][0], "surname key not set");
    }

    public function test_street_not_set()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "xxxxxx" => "Palm street 25-7",
            "state" => "California",
            "zip" => "60744",
            "country" => "US",
            "phone" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertFalse($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey(Orders::ORDER_STREET, $errors);
        $this->assertEquals($errors[Orders::ORDER_STREET][0], "street key not set");
    }

    public function test_state_not_set()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "Palm street 25-7",
            "xxxxx" => "California",
            "zip" => "60744",
            "country" => "US",
            "phone" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertFalse($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey(Orders::ORDER_STATE, $errors);
        $this->assertEquals($errors[Orders::ORDER_STATE][0], "state key not set");
    }

    public function test_zip_not_set()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "Palm street 25-7",
            "state" => "California",
            "xxx" => "60744",
            "country" => "US",
            "phone" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertFalse($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey(Orders::ORDER_ZIP, $errors);
        $this->assertEquals($errors[Orders::ORDER_ZIP][0], "zip code key not set");
    }

    public function test_country_not_set()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "Palm street 25-7",
            "state" => "California",
            "zip" => "60744",
            "xxxxxxx" => "US",
            "phone" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertFalse($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey(Orders::ORDER_COUNTRY, $errors);
        $this->assertEquals($errors[Orders::ORDER_COUNTRY][0], "country key not set");
    }

    public function test_phone_not_set()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "Palm street 25-7",
            "state" => "California",
            "zip" => "60744",
            "country" => "US",
            "xxxxx" => "+1 123 123 123"
        ];

        $domesticAddressValidator = new DomesticAddressValidator(new AlphabeticStringValidator(), new StreetValidator(), new StateValidator(), new ZipCodeValidator(), new CountryValidator(), new PhoneValidator(), new ErrorsLoader());

        $valid = $domesticAddressValidator->validate($ship_to_address);

        $this->assertFalse($valid);

        $errors = $domesticAddressValidator->getErrors();

        $this->assertIsArray($errors);
        $this->assertArrayHasKey(Orders::ORDER_PHONE, $errors);
        $this->assertEquals($errors[Orders::ORDER_PHONE][0], "phone key not set");
    }
}
