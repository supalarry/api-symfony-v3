<?php

namespace App;

use App\Entity\Users;
use App\Exception\ValidateUserException;

class ValidateUser
{
    private $jsonBody;
    private $errors;
    private $alphabeticStringValidator;
    private $errorsLoader;

    public function __construct(array $jsonBody, AlphabeticStringValidator $alphabeticStringValidator, ErrorsLoader $errorsLoader)
    {
        $this->jsonBody = $jsonBody;
        $this->errors = [];
        $this->alphabeticStringValidator = $alphabeticStringValidator;
        $this->errorsLoader = $errorsLoader;
    }

    public function validateKeys(): void
    {
        /* check if keys exist */
        if (!isset($this->jsonBody[Users::USER_NAME]))
            $this->errorsLoader->load(Users::USER_NAME, 'name key not set', $this->errors);
        if (!isset($this->jsonBody[Users::USER_SURNAME]))
            $this->errorsLoader->load(Users::USER_SURNAME, 'surname key not set', $this->errors);

        /* validate key values */
        foreach ($this->jsonBody as $key => $value)
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