<?php

namespace App\Tests;

use App\Entity\Users;
use App\Exception\ValidateUserException;
use App\ValidateUser;
use PHPUnit\Framework\TestCase;

class ValidateUserTest extends TestCase
{
    /** @test */
    public function valid_keys_and_values()
    {
        $json_body = [
            Users::USER_NAME => "John",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        $validator->validateKeys();

        $errors = $validator->getErrors();

        $this->assertEmpty($errors);
    }

    /** @test */
    public function name_key_not_set()
    {
        $json_body = [
            "nameblablabla" => "John",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        try {
            $validator->validateKeys();
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME], 'name key not set');
        }
    }

    /** @test */
    public function name_key_invalid()
    {
        $json_body = [
            Users::USER_NAME => "John55",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        try {
            $validator->validateKeys();
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME], 'Invalid name. It can only consist of letters and can not be empty');
        }
    }

    /** @test */
    public function surname_key_not_set()
    {
        $json_body = [
            Users::USER_NAME => "John",
            "surnameblablabla" => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        try {
            $validator->validateKeys();
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_SURNAME], 'surname key not set');
        }
    }

    /** @test */
    public function surname_key_invalid()
    {
        $json_body = [
            Users::USER_NAME => "John",
            Users::USER_SURNAME => "Doe55"
        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        try {
            $validator->validateKeys();
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_SURNAME], 'Invalid surname. It can only consist of letters and can not be empty');
        }
    }

    /** @test */
    public function name_key_empty()
    {
        $json_body = [
            Users::USER_NAME => "",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        try {
            $validator->validateKeys();
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME], 'Invalid name. It can only consist of letters and can not be empty');
        }
    }

    /** @test */
    public function surname_key_empty()
    {
        $json_body = [
            Users::USER_NAME => "John",
            Users::USER_SURNAME => ""
        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        try {
            $validator->validateKeys();
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_SURNAME], 'Invalid surname. It can only consist of letters and can not be empty');
        }
    }

    /** @test */
    public function no_keys()
    {
        $json_body = [

        ];

        $errors = [];

        $validator = new ValidateUser($json_body);

        try {
            $validator->validateKeys();
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME], 'name key not set');
            $this->assertEquals($errors[Users::USER_SURNAME], 'surname key not set');
        }
    }
}
