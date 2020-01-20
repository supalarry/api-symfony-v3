<?php


namespace App;


use App\Exception\AddressException;

class DomesticAddressValidator
{
    private $alphabeticStringValidator;
    private $streetValidator;
    private $stateValidator;
    private $zipCodeValidator;
    private $countryValidator;
    private $phoneValidator;
    private $errors;
    private $errorsLoader;

    public function __construct(AlphabeticStringValidator $alphabetic, StreetValidator $street, StateValidator $state, ZipCodeValidator $zip, CountryValidator $country, PhoneValidator $phone, ErrorsLoader $errors)
    {
        $this->alphabeticStringValidator = $alphabetic;
        $this->streetValidator = $street;
        $this->stateValidator = $state;
        $this->zipCodeValidator = $zip;
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
        if (!isset($address['state']))
            $this->errorsLoader->load('state', 'state key not set', $this->errors);
        if (!isset($address['zip']))
            $this->errorsLoader->load('zip', 'zip code key not set', $this->errors);
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
            elseif ($key === "state" && !$this->stateValidator->validate($value))
                $this->errorsLoader->load("state", "invalid state", $this->errors);
            elseif ($key === "zip" && !$this->zipCodeValidator->validate($value))
                $this->errorsLoader->load("zip", "invalid zip code", $this->errors);
            elseif ($key === "country" && !$this->countryValidator->validateAlphabetic($value))
                $this->errorsLoader->load("country", "invalid country", $this->errors);
            elseif ($key === "phone" && !$this->phoneValidator->validate($value))
                $this->errorsLoader->load("phone", "invalid phone number", $this->errors);
        }

        if (!empty($this->errors))
            throw new AddressException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}