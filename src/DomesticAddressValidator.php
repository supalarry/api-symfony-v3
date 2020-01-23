<?php


namespace App;


use App\Entity\Orders;
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

    public function validate(array $address): bool
    {
        if (!isset($address[Orders::ORDER_OWNER_NAME]))
            $this->errorsLoader->load(Orders::ORDER_OWNER_NAME, 'name key not set', $this->errors);
        if (!isset($address[Orders::ORDER_OWNER_SURNAME]))
            $this->errorsLoader->load(Orders::ORDER_OWNER_SURNAME, 'surname key not set', $this->errors);
        if (!isset($address[Orders::ORDER_STREET]))
            $this->errorsLoader->load(Orders::ORDER_STREET, 'street key not set', $this->errors);
        if (!isset($address[Orders::ORDER_STATE]))
            $this->errorsLoader->load(Orders::ORDER_STATE, 'state key not set', $this->errors);
        if (!isset($address[Orders::ORDER_ZIP]))
            $this->errorsLoader->load(Orders::ORDER_ZIP, 'zip code key not set', $this->errors);
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
            elseif ($key === Orders::ORDER_STATE && !$this->stateValidator->validate($value))
                $this->errorsLoader->load(Orders::ORDER_STATE, "invalid state", $this->errors);
            elseif ($key === Orders::ORDER_ZIP && !$this->zipCodeValidator->validate($value))
                $this->errorsLoader->load(Orders::ORDER_ZIP, "invalid zip code", $this->errors);
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