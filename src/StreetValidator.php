<?php


namespace App;


class StreetValidator
{
    public function validate($str): bool
    {
        if (preg_match('/^[A-Za-z0-9\-\s]+$/', $str))
            return (true);
        return (false);
    }
}