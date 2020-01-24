<?php


namespace App\Validators\AddressValidators;

use App\Entity\Order;
use App\ErrorsLoader;
use App\Validators\AlphabeticStringValidator;
use App\Validators\AddressValidators\Modules\CountryValidator;
use App\Validators\AddressValidators\Modules\PhoneValidator;
use App\Validators\AddressValidators\Modules\StreetValidator;

class InternationalAddressValidator
{
    private $alphabeticStringValidator;
    private $streetValidator;
    private $countryValidator;
    private $phoneValidator;
    private $errors;
    private $errorsLoader;

    public function __construct(AlphabeticStringValidator $alphabetic, StreetValidator $street, CountryValidator $country, PhoneValidator $phone, ErrorsLoader $errors)
    {
        $this->alphabeticStringValidator = $alphabetic;
        $this->streetValidator = $street;
        $this->countryValidator = $country;
        $this->phoneValidator = $phone;
        $this->errors = [];
        $this->errorsLoader = $errors;
    }

    public function validate(array $address): bool
    {
        if (!isset($address[Order::OWNER_NAME]))
            $this->errorsLoader->load(Order::OWNER_NAME, 'name key not set', $this->errors);
        if (!isset($address[Order::OWNER_SURNAME]))
            $this->errorsLoader->load(Order::OWNER_SURNAME, 'surname key not set', $this->errors);
        if (!isset($address[Order::STREET]))
            $this->errorsLoader->load(Order::STREET, 'street key not set', $this->errors);
        if (!isset($address[Order::COUNTRY]))
            $this->errorsLoader->load(Order::COUNTRY, 'country key not set', $this->errors);
        if (!isset($address[Order::PHONE]))
            $this->errorsLoader->load(Order::PHONE, 'phone key not set', $this->errors);

        foreach ($address as $key => $value)
        {
            if ($key === Order::OWNER_NAME && !$this->alphabeticStringValidator->validate($value))
                $this->errorsLoader->load(Order::OWNER_NAME, "name can only consist of letters", $this->errors);
            elseif ($key === Order::OWNER_SURNAME && !$this->alphabeticStringValidator->validate($value))
                $this->errorsLoader->load(Order::OWNER_SURNAME, "surname can only consist of letters", $this->errors);
            elseif ($key === Order::STREET && !$this->streetValidator->validate($value))
                $this->errorsLoader->load(Order::STREET, "street can only consist of letters, digits, dash (-) and whitespaces", $this->errors);
            elseif ($key === Order::COUNTRY && !$this->countryValidator->validateAlphabetic($value))
                $this->errorsLoader->load(Order::COUNTRY, "invalid country", $this->errors);
            elseif ($key === Order::PHONE && !$this->phoneValidator->validate($value))
                $this->errorsLoader->load(Order::PHONE, "invalid phone number", $this->errors);
        }

        if (!empty($this->errors))
            return (false);

        return (true);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}