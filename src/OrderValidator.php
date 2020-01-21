<?php


namespace App;

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
        if (!array_key_exists("ship_to_address", $request_body))
            $this->errors["ship_to_address"] = 'shipping address not set';
        if (!array_key_exists("line_items", $request_body))
            $this->errors["line_items"] = 'order does not contain any products';

        if (array_key_exists("ship_to_address", $request_body) && !$this->addressValidator->validate($request_body['ship_to_address']))
            $this->errors["ship_to_address"] = $this->addressValidator->getErrors();
        if (array_key_exists("line_items", $request_body) && !$this->lineItemsValidator->validate($id_user, $request_body['line_items']))
            $this->errors["line_items"] = $this->lineItemsValidator->getErrors();

        if (!empty($this->errors))
            throw new OrderValidatorException($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}