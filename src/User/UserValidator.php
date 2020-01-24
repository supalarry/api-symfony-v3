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
            $this->errorsLoader->load(User::NAME, 'name key not set', $this->errors);
        if (!isset($data[User::SURNAME]))
            $this->errorsLoader->load(User::SURNAME, 'surname key not set', $this->errors);

        foreach ($data as $key => $value)
        {
            if ($key === User::NAME && !$this->alphabeticStringValidator->validate($value))
                $this->errorsLoader->load(User::NAME, 'Invalid name. It can only consist of letters and can not be empty', $this->errors);
            elseif ($key === User::SURNAME && !$this->alphabeticStringValidator->validate($value))
                $this->errorsLoader->load(User::SURNAME, 'Invalid surname. It can only consist of letters and can not be empty', $this->errors);
        }

        if (!empty($this->errors))
            throw new UserValidatorException($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}