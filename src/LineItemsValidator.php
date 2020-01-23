<?php


namespace App;


use App\Entity\Orders;
use App\Entity\Products;
use App\Exception\LineItemsValidatorException;
use App\Interfaces\IProductsRepository;

class LineItemsValidator
{
    private $repository;
    private $errors;
    private $errorsLoader;

    public function __construct(IProductsRepository $repository, ErrorsLoader $errorsLoader)
    {
        $this->repository = $repository;
        $this->errors = [];
        $this->errorsLoader = $errorsLoader;
    }

    public function validate(int $id_user, array $line_items): bool
    {
        $emptyOrder = true;
        $itemNumber = 1;

        foreach ($line_items as $item)
        {
            if (!array_key_exists(Orders::PRODUCT_ID, $item))
                $this->errorsLoader->load(Products::PRODUCT_ID, "id field not set for line item number " . $itemNumber, $this->errors);
            if (!array_key_exists(Orders::PRODUCT_QUANTITY, $item))
                $this->errorsLoader->load(Orders::PRODUCT_QUANTITY, "quantity field not set for line item number " . $itemNumber, $this->errors);
            if (array_key_exists(Orders::PRODUCT_ID, $item) && !$this->repository->getById($id_user, $item[Orders::PRODUCT_ID]))
                $this->errorsLoader->load(Orders::PRODUCT_ID, "invalid product id for user with id of " . $id_user . " for line item number " . $itemNumber, $this->errors);
            if (array_key_exists(Orders::PRODUCT_QUANTITY, $item) && $item[Orders::PRODUCT_QUANTITY] <= 0)
                $this->errorsLoader->load(Orders::PRODUCT_QUANTITY, "quantity must be at least 1 for line item number " . $itemNumber, $this->errors);
            if (empty($this->errors))
                $emptyOrder = false;
            $itemNumber++;
        }

        if ($emptyOrder)
            $this->errorsLoader->load("line_items", "order must contain at least 1 product with a valid id and quantity", $this->errors);
        if (!empty($this->errors))
            return (false);
        return (true);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}