<?php


namespace App;


use App\Entity\Orders;
use App\Exception\AddressException;

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
        if (!isset($address[Orders::ORDER_OWNER_NAME]))
            $this->errorsLoader->load(Orders::ORDER_OWNER_NAME, 'name key not set', $this->errors);
        if (!isset($address[Orders::ORDER_OWNER_SURNAME]))
            $this->errorsLoader->load(Orders::ORDER_OWNER_SURNAME, 'surname key not set', $this->errors);
        if (!isset($address[Orders::ORDER_STREET]))
            $this->errorsLoader->load(Orders::ORDER_STREET, 'street key not set', $this->errors);
        if (!isset($address[Orders::ORDER_COUNTRY]))
            $this->errorsLoader->load(Orders::ORDER_COUNTRY, 'country key not set', $this->errors);
        if (!isset($address[Orders::ORDER_PHONE]))
            $this->errorsLoader->load(Orders::ORDER_PHONE, 'phone key not set', $this->errors);

        foreach ($address as $key => $value)
        {
            if ($key === Orders::ORDER_OWNER_NAME && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errorsLoader->load(Orders::ORDER_OWNER_NAME, "name can only consist of letters", $this->errors);
            elseif ($key === Orders::ORDER_OWNER_SURNAME && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errorsLoader->load(Orders::ORDER_OWNER_SURNAME, "surname can only consist of letters", $this->errors);
            elseif ($key === Orders::ORDER_STREET && !$this->streetValidator->validate($value))
                $this->errorsLoader->load(Orders::ORDER_STREET, "street can only consist of letters, digits, dash (-) and whitespaces", $this->errors);
            elseif ($key === Orders::ORDER_COUNTRY && !$this->countryValidator->validateAlphabetic($value))
                $this->errorsLoader->load(Orders::ORDER_COUNTRY, "invalid country", $this->errors);
            elseif ($key === Orders::ORDER_PHONE && !$this->phoneValidator->validate($value))
                $this->errorsLoader->load(Orders::ORDER_PHONE, "invalid phone number", $this->errors);
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