<?php


namespace App;


use App\Exception\AddressException;
use App\Exception\LineItemsValidatorException;

class OrderValidator
{
    private $addressValidator;
    private $lineItemsValidator;

    public function __construct(AddressValidator $addressValidator, LineItemsValidator $lineItemsValidator)
    {
        $this->addressValidator = $addressValidator;
        $this->lineItemsValidator = $lineItemsValidator;
    }

    public function validate(int $id_user, array $request_body)
    {
        if (!array_key_exists("ship_to_address", $request_body))
            throw new AddressException(["ship_to_address" => "shipping address not set"]);
        if (!array_key_exists("line_items", $request_body))
            throw new AddressException(["line_items" => "order does not contain any products"]);

        try {
            $this->addressValidator->validate($request_body['ship_to_address']);
            $this->lineItemsValidator->validate($id_user, $request_body['line_items']);
        } catch (AddressException $e){

        } catch (LineItemsValidatorException $e){

        }
    }
}