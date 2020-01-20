<?php

namespace App\Tests;

use App\Exception\ShipmentTypeException;
use App\ShipmentType;
use PHPUnit\Framework\TestCase;

class ShipmentTypeTest extends TestCase
{
    public function test_is_domestic()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "Palm street 25-7",
            "state" => "California",
            "ZIP" => "60744",
            "country" => "US",
            "phone" => "+1 123 123 123"
        ];
        $shipmentType = new ShipmentType();

        $international = $shipmentType->isInternational($ship_to_address);
        $this->assertFalse($international);

        $domestic = $shipmentType->isDomestic($ship_to_address);
        $this->assertTrue($domestic);
    }

    public function test_is_international()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "pirma iela 20-7",
            "state" => "Adazu novads",
            "ZIP" => "60744",
            "country" => "Latvia",
            "phone" => "+371 28 222 222"
        ];
        $shipmentType = new ShipmentType();

        $international = $shipmentType->isInternational($ship_to_address);
        $this->assertTrue($international);

        $domestic = $shipmentType->isDomestic($ship_to_address);
        $this->assertFalse($domestic);
    }

    public function test_country_not_set()
    {
        $ship_to_address = [
            "name" => "John",
            "surname" => "Doe",
            "street" => "pirma iela 20-7",
            "state" => "Adazu novads",
            "ZIP" => "60744",
            "xxxxxxx" => "Latvia",
            "phone" => "+371 28 222 222"
        ];

        $this->expectException(ShipmentTypeException::class);

        $shipmentType = new ShipmentType();
        $shipmentType->isDomestic($ship_to_address);
    }
}
