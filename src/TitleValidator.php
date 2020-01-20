<?php


namespace App;


class TitleValidator
{
    public function validate($str): int
    {
        if (preg_match('/^[A-Za-z0-9\-\s]+$/', $str))
            return (1);
        return (0);
    }
}