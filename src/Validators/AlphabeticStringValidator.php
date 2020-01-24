<?php

namespace App\Validators;

class AlphabeticStringValidator
{
    public function validate($str): bool
    {
        if (preg_match('/^[A-Za-z]+$/', $str))
            return (true);
        return (false);
    }
}