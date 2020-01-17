<?php


namespace App;


use App\Entity\Products;
use App\Exception\ValidateProductException;

class ValidateProduct
{
    private $jsonBody;
    private $errors;
    private $userIdValidator;
    private $productTypeValidator;
    private $skuValidator;
    private $titleValidator;
    private $errorsLoader;

    public function __construct(array $jsonBody, ProductTypeValidator $productTypeV, TitleValidator $titleV, SkuValidator $skuV, ErrorsLoader $errorsLoader)
    {
        $this->jsonBody = $jsonBody;
        $this->errors = [];
        $this->productTypeValidator = $productTypeV;
        $this->skuValidator = $skuV;
        $this->titleValidator = $titleV;
        $this->errorsLoader = $errorsLoader;
    }

    public function validateKeys(): void
    {
        /* check if keys exist */
        if (!isset($this->jsonBody[Products::PRODUCT_TYPE]))
            $this->errorsLoader->load(Products::PRODUCT_TYPE, 'type key not set', $this->errors);
        if (!isset($this->jsonBody[Products::PRODUCT_TITLE]))
            $this->errorsLoader->load(Products::PRODUCT_TITLE, 'title key not set', $this->errors);
        if (!isset($this->jsonBody[Products::PRODUCT_SKU]))
            $this->errorsLoader->load(Products::PRODUCT_SKU, 'sku key not set', $this->errors);
        if (!isset($this->jsonBody[Products::PRODUCT_COST]))
            $this->errorsLoader->load(Products::PRODUCT_COST, 'cost key not set', $this->errors);

        /* validate key values */
        foreach ($this->jsonBody as $key => $value)
        {
            if ($key === Products::PRODUCT_TYPE && $this->productTypeValidator->validate($value) != 1)
                $this->errorsLoader->load(Products::PRODUCT_TYPE, 'Invalid type. Allowed types: ' . $this->productTypeValidator->getAllowed(), $this->errors);
            elseif ($key === Products::PRODUCT_TITLE && $this->titleValidator->validate($value) != 1)
                $this->errorsLoader->load(Products::PRODUCT_TITLE, 'Invalid title. It can only consist of letters, digits and dash(-)', $this->errors);
            elseif ($key === Products::PRODUCT_SKU && $this->skuValidator->validate($value) != 1)
                $this->errorsLoader->load(Products::PRODUCT_SKU, 'Invalid SKU. It must be unique, and it appears another product already has it', $this->errors);
            elseif ($key === Products::PRODUCT_COST && !is_int($value))
                $this->errorsLoader->load(Products::PRODUCT_COST, 'Invalid cost. It must be an integer describing price with smallest money unit', $this->errors);
        }

        if (!empty($this->errors))
            throw new ValidateProductException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}