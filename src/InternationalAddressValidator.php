<?php


namespace App;


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

    public function validate(array $address)
    {
        if (!isset($address['name']))
            $this->errorsLoader->load('name', 'name key not set', $this->errors);
        if (!isset($address['surname']))
            $this->errorsLoader->load('surname', 'surname key not set', $this->errors);
        if (!isset($address['street']))
            $this->errorsLoader->load('street', 'street key not set', $this->errors);
        if (!isset($address['country']))
            $this->errorsLoader->load('country', 'country key not set', $this->errors);
        if (!isset($address['phone']))
            $this->errorsLoader->load('phone', 'phone key not set', $this->errors);

        foreach ($address as $key => $value)
        {
            if ($key === "name" && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errorsLoader->load("name", "name can only consist of letters", $this->errors);
            elseif ($key === "surname" && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errorsLoader->load("surname", "surname can only consist of letters", $this->errors);
            elseif ($key === "street" && !$this->streetValidator->validate($value))
                $this->errorsLoader->load("street", "street can only consist of letters, digits, dash (-) and whitespaces", $this->errors);
                $this->errorsLoader->load("zip", "invalid zip code", $this->errors);
            elseif ($key === "country" && !$this->countryValidator->validateAlphabetic($value))
                $this->errorsLoader->load("country", "invalid country", $this->errors);
            elseif ($key === "phone" && !$this->phoneValidator->validate($value))
                $this->errorsLoader->load("phone", "invalid phone number", $this->errors);
        }

        // this could return 0 if there are errors
        if (!empty($this->errors))
            throw new AddressException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}