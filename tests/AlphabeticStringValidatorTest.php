<?php

namespace App\Tests;

use App\AlphabeticStringValidator;
use PHPUnit\Framework\TestCase;

class AlphabeticStringValidatorTest extends TestCase
{
    public function test_valid_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->valid("John");
        $this->assertEquals($response, 1);
    }

    public function test_invalid_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->valid("John5");
        $this->assertEquals($response, 0);
    }

    public function test_empty_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->valid("");
        $this->assertEquals($response, 0);
    }
}
