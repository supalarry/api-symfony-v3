<?php


namespace App\Product;


use App\Entity\Product;
use App\ErrorsLoader;
use App\Exception\ProductValidatorException;
use App\Validators\ProductValidators\ProductTypeValidator;
use App\Validators\ProductValidators\SkuValidator;
use App\Validators\ProductValidators\TitleValidator;

class ProductValidator
{
    private $errors;
    private $productTypeValidator;
    private $skuValidator;
    private $titleValidator;
    private $errorsLoader;

    public function __construct(ProductTypeValidator $productTypeV, TitleValidator $titleV, SkuValidator $skuV, ErrorsLoader $errorsLoader)
    {
        $this->errors = [];
        $this->productTypeValidator = $productTypeV;
        $this->skuValidator = $skuV;
        $this->titleValidator = $titleV;
        $this->errorsLoader = $errorsLoader;
    }

    public function validate(array $data, int $id_user): void
    {
        if (!isset($data[Product::TYPE]))
            $this->errorsLoader->load(Product::TYPE, 'type key not set', $this->errors);
        if (!isset($data[Product::TITLE]))
            $this->errorsLoader->load(Product::TITLE, 'title key not set', $this->errors);
        if (!isset($data[Product::SKU]))
            $this->errorsLoader->load(Product::SKU, 'sku key not set', $this->errors);
        if (!isset($data[Product::COST]))
            $this->errorsLoader->load(Product::COST, 'cost key not set', $this->errors);

        foreach ($data as $key => $value)
        {
            if ($key === Product::TYPE && !$this->productTypeValidator->validate($value))
                $this->errorsLoader->load(Product::TYPE, 'Invalid type. Allowed types: ' . $this->productTypeValidator->getAllowed(), $this->errors);
            elseif ($key === Product::TITLE && !$this->titleValidator->validate($value))
                $this->errorsLoader->load(Product::TITLE, 'Invalid title. It can only consist of letters, digits and dash(-)', $this->errors);
            elseif ($key === Product::SKU && !$this->skuValidator->validate($value, $id_user))
                $this->errorsLoader->load(Product::SKU, 'Invalid SKU. It must be unique, and it appears another product already has it', $this->errors);
            elseif ($key === Product::COST && !is_int($value))
                $this->errorsLoader->load(Product::COST, 'Invalid cost. It must be an integer describing price with smallest money unit', $this->errors);
        }

        if (!empty($this->errors))
            throw new ProductValidatorException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}