<?php


namespace App;

use App\Entity\Orders;
use App\Exception\AddressException;

class ShipmentType
{
    const DOMESTIC_CODE = "US";
    const DOMESTIC_CODE3 = "USA";
    const DOMESTIC_NAME = "United States of America";
    private $errors = [];

    public function getType(array $ship_to_address)
    {
        if (!array_key_exists(Orders::ORDER_COUNTRY, $ship_to_address))
        {
            $this->errors[Orders::ORDER_COUNTRY] = "country key not set";
            return (null);
        }

        if ($ship_to_address[Orders::ORDER_COUNTRY] === self::DOMESTIC_CODE
            || $ship_to_address[Orders::ORDER_COUNTRY] === self::DOMESTIC_CODE3
            || $ship_to_address[Orders::ORDER_COUNTRY] === self::DOMESTIC_NAME)
            return (Orders::DOMESTIC_ORDER);

        return (Orders::INTERNATIONAL_ORDER);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}