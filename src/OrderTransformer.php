<?php


namespace App;


use App\Entity\Orders;
use App\Interfaces\IReturn;

class OrderTransformer
{
    public function transform(Orders $order, array $dataArray)
    {
        if (!array_key_exists(Orders::ORDER_INFO,$dataArray))
            $dataArray[Orders::ORDER_INFO] = array();
        $dataArray[Orders::ORDER_INFO][Orders::ORDER_ID] = $order->getId();
        $dataArray[Orders::ORDER_INFO][Orders::ORDER_PRODUCTION_COST] = $order->getProductionCost();
        $dataArray[Orders::ORDER_INFO][Orders::ORDER_SHIPPING_COST] = $order->getShippingCost();
        $dataArray[Orders::ORDER_INFO][Orders::ORDER_TOTAL_COST] = $order->getTotalCost();

        return ($dataArray);
    }
}