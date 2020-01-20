<?php


namespace App;


use App\Exception\AddressException;

class AddressValidator
{
    private $shipmentType;
    private $domesticShipmentValidator;
    private $internationalShipmentValidator;

    public function __construct(ShipmentType $shipmentType, DomesticAddressValidator $domesticAddressValidator, InternationalAddressValidator $internationalAddressValidator)
    {
        $this->shipmentType = $shipmentType;
        $this->domesticShipmentValidator = $domesticAddressValidator;
        $this->internationalShipmentValidator = $internationalAddressValidator;
    }

    public function validate(array $ship_to_address)
    {
        // if shipmenttype returns domestic, call domestic and check its return
        // if shipmenttype returns international, call international and check if international validates
        // if one of thouse returns 0 $valid = $this->domestic..Validator->validate, get their errors and throw an exception at this level
        try {
            if ($this->shipmentType->isDomestic($ship_to_address))
                $this->domesticShipmentValidator->validate($ship_to_address);
            else
                $this->internationalShipmentValidator->validate($ship_to_address);
        } catch (AddressException $e){
            throw $e;
        }
    }
}