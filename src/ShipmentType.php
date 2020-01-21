<?php


namespace App;

use App\Exception\AddressException;

class ShipmentType
{
    const DOMESTIC_CODE = "US";
    const DOMESTIC_CODE3 = "USA";
    const DOMESTIC_NAME = "United States of America";
    private $errors = [];

    public function getType(array $ship_to_address)
    {
        if (!array_key_exists("country", $ship_to_address))
        {
            $this->errors["country"] = "country key not set";
            return (null);
        }

        if ($ship_to_address["country"] === self::DOMESTIC_CODE
            || $ship_to_address["country"] === self::DOMESTIC_CODE3
            || $ship_to_address["country"] === self::DOMESTIC_NAME)
            return ("domestic");

        return ("international");
    }

    public function getErrors()
    {
        return $this->errors;
    }
}