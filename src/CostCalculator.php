<?php


namespace App;


use App\Entity\Orders;

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
        $shipmentType = $this->shipmentType->getType($request_body[Orders::ORDER_SHIPPING_DATA]);
        if ($shipmentType === Orders::DOMESTIC_ORDER)
            return ($this->domesticCostCalculator->calculate($id_user, $request_body[Orders::ORDER_LINE_ITEMS]));
        elseif ($shipmentType === Orders::INTERNATIONAL_ORDER)
            return ($this->internationalCostCalculator->calculate($id_user, $request_body[Orders::ORDER_LINE_ITEMS]));
    }
}