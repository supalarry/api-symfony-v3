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
        {
            if ($this->express_shipping($request_body))
                return ($this->domesticCostCalculator->calculate_express($id_user, $request_body[Orders::ORDER_LINE_ITEMS]));
            return ($this->domesticCostCalculator->calculate($id_user, $request_body[Orders::ORDER_LINE_ITEMS]));
        }
        elseif ($shipmentType === Orders::INTERNATIONAL_ORDER)
            return ($this->internationalCostCalculator->calculate($id_user, $request_body[Orders::ORDER_LINE_ITEMS]));
    }

    public static function express_shipping(array $request_body):bool
    {
        if (array_key_exists(Orders::ORDER_INFO, $request_body)
            && array_key_exists(Orders::EXPRESS_SHIPPING, $request_body[Orders::ORDER_INFO])
            && $request_body[Orders::ORDER_INFO][Orders::EXPRESS_SHIPPING] === true)
                return (true);
        return (false);
    }
}