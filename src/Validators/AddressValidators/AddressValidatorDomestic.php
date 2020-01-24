<?php


namespace App\Validators\AddressValidators;

use App\Entity\Order;
use App\ErrorsLoader;
use App\Validators\AlphabeticStringValidator;
use App\Validators\AddressValidators\Modules\CountryValidator;
use App\Validators\AddressValidators\Modules\PhoneValidator;
use App\Validators\AddressValidators\Modules\StateValidator;
use App\Validators\AddressValidators\Modules\StreetValidator;
use App\Validators\AddressValidators\Modules\ZipCodeValidator;

class AddressValidatorDomestic
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
        if (!isset($address[Order::OWNER_NAME]))
            $this->errorsLoader->load(Order::OWNER_NAME, 'name key not set', $this->errors);
        if (!isset($address[Order::OWNER_SURNAME]))
            $this->errorsLoader->load(Order::OWNER_SURNAME, 'surname key not set', $this->errors);
        if (!isset($address[Order::STREET]))
            $this->errorsLoader->load(Order::STREET, 'street key not set', $this->errors);
        if (!isset($address[Order::STATE]))
            $this->errorsLoader->load(Order::STATE, 'state key not set', $this->errors);
        if (!isset($address[Order::ZIP]))
            $this->errorsLoader->load(Order::ZIP, 'zip code key not set', $this->errors);
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
            elseif ($key === Order::STATE && !$this->stateValidator->validate($value))
                $this->errorsLoader->load(Order::STATE, "invalid state", $this->errors);
            elseif ($key === Order::ZIP && !$this->zipCodeValidator->validate($value))
                $this->errorsLoader->load(Order::ZIP, "invalid zip code", $this->errors);
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