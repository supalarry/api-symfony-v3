<?php


namespace App;


use App\Entity\Order;
use App\Interfaces\IReturn;

class OrderTransformer
{
    public function transform(Order $order, array $dataArray)
    {
        if ($order->getExpressShipping() !== true && array_key_exists(Order::ORDER_INFO, $dataArray) && array_key_exists(Order::EXPRESS_SHIPPING, $dataArray[Order::ORDER_INFO]))
            unset($dataArray[Order::ORDER_INFO][Order::EXPRESS_SHIPPING]);

        $dataArray[Order::ORDER_INFO][Order::ORDER_ID] = $order->getId();
        $dataArray[Order::ORDER_INFO][Order::ORDER_PRODUCTION_COST] = $order->getProductionCost();
        $dataArray[Order::ORDER_INFO][Order::ORDER_SHIPPING_COST] = $order->getShippingCost();
        $dataArray[Order::ORDER_INFO][Order::ORDER_TOTAL_COST] = $order->getTotalCost();

        return ($dataArray);
    }
}