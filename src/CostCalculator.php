<?php


namespace App;


class CostCalculator
{
    private $shipmentType;
    private $domesticCostCalculator;
    private $internationalCostCalculator;

    public function __construct(ShipmentType $shipmentType, DomesticCostCalculator $domesticCostCalculator, InternationalCostCalculator $internationalCostCalculator)
    {
        $this->shipmentType = $shipmentType;
        $this->domesticCostCalculator = $domesticCostCalculator;
        $this->internationalCostCalculator = $internationalCostCalculator;
    }

    public function calculate(int $id_user, array $request_body): array
    {
        $shipmentType = $this->shipmentType->getType($request_body["ship_to_address"]);
        if ($shipmentType === "domestic")
            return ($this->domesticCostCalculator->calculate($id_user, $request_body["line_items"]));
        elseif ($shipmentType === "international")
            return ($this->internationalCostCalculator->calculate($id_user, $request_body["line_items"]));
    }
}