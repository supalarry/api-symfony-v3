<?php

namespace App\User;

use App\Entity\User;
use App\ErrorsLoader;
use App\Exception\UserValidatorException;
use App\Validators\AlphabeticStringValidator;

class UserValidator
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

    public function validate(array $data): void
    {
        if (!isset($data[User::NAME]))
            $this->errorsLoader->load(User::NAME, User::NO_NAME, $this->errors);
        if (!isset($data[User::SURNAME]))
            $this->errorsLoader->load(User::SURNAME, User::NO_SURNAME, $this->errors);

        foreach ($data as $key => $value)
        {
            if ($key === User::NAME && !$this->alphabeticStringValidator->validate($value))
                $this->errorsLoader->load(User::NAME, User::INVALID_NAME, $this->errors);
            elseif ($key === User::SURNAME && !$this->alphabeticStringValidator->validate($value))
                $this->errorsLoader->load(User::SURNAME, User::INVALID_SURNAME, $this->errors);
        }

        if (!empty($this->errors))
            throw new UserValidatorException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}