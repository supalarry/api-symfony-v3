<?php


namespace App;


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
            if (!array_key_exists("id", $item))
                $this->errorsLoader->load("id", "id field not set for line item number " . $itemNumber, $this->errors);
            if (!array_key_exists("quantity", $item))
                $this->errorsLoader->load("quantity", "quantity field not set for line item number " . $itemNumber, $this->errors);
            if (array_key_exists("id", $item) && !$this->repository->getById($id_user, $item['id']))
                $this->errorsLoader->load("id", "invalid product id for user with id of " . $id_user . " for line item number " . $itemNumber, $this->errors);
            if (array_key_exists("quantity", $item) && $item['quantity'] <= 0)
                $this->errorsLoader->load("quantity", "quantity must be at least 1 for line item number " . $itemNumber, $this->errors);
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