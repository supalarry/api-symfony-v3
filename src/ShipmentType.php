<?php


namespace App;

use App\Exception\AddressException;

class ShipmentType
{
    const DOMESTIC_CODE = "US";
    const DOMESTIC_CODE3 = "USA";
    const DOMESTIC_NAME = "United States of America";

    // we could have a function that returns a string either domestic, international or null if there is no country set
    public function isDomestic(array $ship_to_address): bool
    {
        if (!array_key_exists("country", $ship_to_address))
            throw new AddressException(["country" => "country key not set"]);

        if ($ship_to_address["country"] === self::DOMESTIC_CODE
            || $ship_to_address["country"] === self::DOMESTIC_CODE3
            || $ship_to_address["country"] === self::DOMESTIC_NAME)
            return (true);

        return (false);
    }

    public function isInternational(array $ship_to_address): bool
    {
        if (!array_key_exists("country", $ship_to_address))
            throw new AddressException(["country" => "country key not set"]);

        if ($ship_to_address["country"] !== self::DOMESTIC_CODE
            && $ship_to_address["country"] !== self::DOMESTIC_CODE3
            && $ship_to_address["country"] !== self::DOMESTIC_NAME)
            return (true);

        return (false);
    }
}