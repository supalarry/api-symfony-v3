<?php

namespace App;

class AlphabeticStringValidator
{
    public function valid($str): int
    {
        if (preg_match('/^[A-Za-z]+$/', $str))
            return (1);
        return (0);
    }
}