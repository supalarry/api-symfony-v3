<?php


namespace App;

use App\Entity\Orders;
use App\Exception\OrderValidatorException;

class OrderValidator
{
    private $addressValidator;
    private $lineItemsValidator;
    private $errors;

    public function __construct(AddressValidator $addressValidator, LineItemsValidator $lineItemsValidator)
    {
        $this->addressValidator = $addressValidator;
        $this->lineItemsValidator = $lineItemsValidator;
        $this->errors = [];
    }

    public function validate(int $id_user, array $request_body)
    {
        if (!array_key_exists(Orders::ORDER_SHIPPING_DATA, $request_body))
            $this->errors[Orders::ORDER_SHIPPING_DATA] = 'shipping address not set';
        if (!array_key_exists(Orders::ORDER_LINE_ITEMS, $request_body))
            $this->errors[Orders::ORDER_LINE_ITEMS] = 'order does not contain any products';

        if (array_key_exists(Orders::ORDER_SHIPPING_DATA, $request_body) && !$this->addressValidator->validate($request_body[Orders::ORDER_SHIPPING_DATA]))
            $this->errors[Orders::ORDER_SHIPPING_DATA] = $this->addressValidator->getErrors();
        if (array_key_exists(Orders::ORDER_LINE_ITEMS, $request_body) && !$this->lineItemsValidator->validate($id_user, $request_body[Orders::ORDER_LINE_ITEMS]))
            $this->errors[Orders::ORDER_LINE_ITEMS] = $this->lineItemsValidator->getErrors();

        if (!empty($this->errors))
            throw new OrderValidatorException($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}