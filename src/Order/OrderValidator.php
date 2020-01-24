<?php


namespace App\Order;

use App\Entity\Order;
use App\Entity\Product;
use App\Exception\OrderValidatorException;
use App\Interfaces\IProductRepo;
use App\Validators\AddressValidators\AddressValidator;

class OrderValidator
{
    private $addressValidator;
    private $repository;
    private $errors;

    public function __construct(AddressValidator $addressValidator, IProductRepo $repository)
    {
        $this->addressValidator = $addressValidator;
        $this->repository = $repository;
        $this->errors = [];
    }

    public function validate(int $id_user, array $request_body)
    {
        if (!array_key_exists(Order::SHIPPING_DATA, $request_body))
            $this->errors[Order::SHIPPING_DATA] = 'shipping address not set';
        if (!array_key_exists(Order::LINE_ITEMS, $request_body))
            $this->errors[Order::LINE_ITEMS] = 'order does not contain any products';

        if (array_key_exists(Order::SHIPPING_DATA, $request_body) && !$this->addressValidator->validate($request_body[Order::SHIPPING_DATA]))
            $this->errors[Order::SHIPPING_DATA] = $this->addressValidator->getErrors();
        if (array_key_exists(Order::LINE_ITEMS, $request_body) && !$this->validateLineItems($id_user, $request_body[Order::LINE_ITEMS]))
            $this->errors[Order::LINE_ITEMS] = $this->getErrors();

        if (!empty($this->errors))
            throw new OrderValidatorException($this->errors);
    }

    public function validateLineItems(int $id_user, array $line_items): bool
    {
        $emptyOrder = true;
        $itemNumber = 1;

        foreach ($line_items as $item)
        {
            if (!array_key_exists(Product::ID, $item))
                $this->errors[Product::ID] = "id field not set for line item number " . $itemNumber;
            if (!array_key_exists(Product::QUANTITY, $item))
                $this->errors[Product::QUANTITY] = "quantity field not set for line item number " . $itemNumber;
            if (array_key_exists(Product::ID, $item) && !$this->repository->getById($id_user, $item[Product::ID]))
                $this->errors[Product::ID] = "invalid product id for user with id of " . $id_user . " for line item number " . $itemNumber;
            if (array_key_exists(Product::QUANTITY, $item) && $item[Product::QUANTITY] <= 0)
                $this->errors[Product::QUANTITY] = "quantity must be at least 1 for line item number " . $itemNumber;
            if (empty($this->errors))
                $emptyOrder = false;
            $itemNumber++;
        }

        if ($emptyOrder)
            $this->errors[Order::LINE_ITEMS] = "order must contain at least 1 product with a valid id and quantity";
        if (!empty($this->errors))
            return (false);
        return (true);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}