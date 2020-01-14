<?php

namespace App;

use App\Entity\Users;
use App\Exception\ValidateUserException;

class ValidateUser
{
    private $jsonBody;
    private $errors;
    private $alphabeticStringValidator;

    public function __construct(array $jsonBody)
    {
        $this->jsonBody = $jsonBody;
        $this->errors = [];
        $this->alphabeticStringValidator = new AlphabeticStringValidator();
    }

    public function validateKeys(): void
    {
        /* check if keys exist */
        if (!isset($this->jsonBody[Users::USER_NAME]))
            $this->errors[Users::USER_NAME] = 'name key not set';
        if (!isset($this->jsonBody[Users::USER_SURNAME]))
            $this->errors[Users::USER_SURNAME] = 'surname key not set';

        /* validate keys */
        foreach ($this->jsonBody as $key => $value)
        {
            if ($key === Users::USER_NAME && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errors[Users::USER_NAME] = 'Invalid name. It can only consist of letters and can not be empty';
            elseif ($key === Users::USER_SURNAME && $this->alphabeticStringValidator->valid($value) != 1)
                $this->errors[Users::USER_SURNAME] = 'Invalid surname. It can only consist of letters and can not be empty';
        }

        if (!empty($this->errors))
            throw new ValidateUserException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}