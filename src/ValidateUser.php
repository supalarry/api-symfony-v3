<?php

namespace App;

use App\Entity\Users;
use App\Exception\ValidateUserException;

class ValidateUser
{
    private $errors;
    private $alphabeticStringValidator;
    private $errorsLoader;

    public function __construct(AlphabeticStringValidator $alphabeticStringValidator, ErrorsLoader $errorsLoader)
    {
        $this->errors = [];
        $this->alphabeticStringValidator = $alphabeticStringValidator;
        $this->errorsLoader = $errorsLoader;
    }

    public function validateKeys(array $data): void
    {
        if (!isset($data[Users::USER_NAME]))
            $this->errorsLoader->load(Users::USER_NAME, 'name key not set', $this->errors);
        if (!isset($data[Users::USER_SURNAME]))
            $this->errorsLoader->load(Users::USER_SURNAME, 'surname key not set', $this->errors);

        foreach ($data as $key => $value)
        {
            if ($key === Users::USER_NAME && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errorsLoader->load(Users::USER_NAME, 'Invalid name. It can only consist of letters and can not be empty', $this->errors);
            elseif ($key === Users::USER_SURNAME && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errorsLoader->load(Users::USER_SURNAME, 'Invalid surname. It can only consist of letters and can not be empty', $this->errors);
        }

        if (!empty($this->errors))
            throw new ValidateUserException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}