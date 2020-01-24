<?php


namespace App;

class ErrorsLoader
{
    public function load(string $key, string $error, array &$errors)
    {
        if (!array_key_exists($key, $errors))
            $errors[$key] = array();
        array_push($errors[$key], $error);
    }
}