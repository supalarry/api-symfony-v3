<?php

namespace App\Tests;

use App\AlphabeticStringValidator;
use PHPUnit\Framework\TestCase;

class AlphabeticStringValidatorTest extends TestCase
{
    /** @test */
    public function valid_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->valid("John");
        $this->assertEquals($response, 1);
    }

    /** @test */
    public function invalid_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->valid("John5");
        $this->assertEquals($response, 0);
    }

    /** @test */
    public function empty_string()
    {
        $validator = new AlphabeticStringValidator();
        $response = $validator->valid("");
        $this->assertEquals($response, 0);
    }
}
