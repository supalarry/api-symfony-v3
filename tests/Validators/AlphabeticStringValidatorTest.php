<?php

namespace App\Tests;

use App\Validators\AlphabeticStringValidator;
use PHPUnit\Framework\TestCase;

class AlphabeticStringValidatorTest extends TestCase
{
    public function test_valid_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->validate("John");
        $this->assertEquals($response, 1);
    }

    public function test_invalid_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->validate("John5");
        $this->assertEquals($response, 0);
    }

    public function test_empty_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->validate("");
        $this->assertEquals($response, 0);
    }
}
