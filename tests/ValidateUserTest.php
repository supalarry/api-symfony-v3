<?php

namespace App\Tests;

use App\AlphabeticStringValidator;
use App\Entity\Users;
use App\ErrorsLoader;
use App\Exception\ValidateUserException;
use App\ValidateUser;
use PHPUnit\Framework\TestCase;

class ValidateUserTest extends TestCase
{
    public function test_valid_keys_and_values()
    {
        $json_body = [
            Users::USER_NAME => "John",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        $validator->validateKeys($json_body);

        $errors = $validator->getErrors();

        $this->assertEmpty($errors);
    }

    public function test_name_key_not_set()
    {
        $json_body = [
            "nameblablabla" => "John",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        try {
            $validator->validateKeys($json_body);
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME][0], 'name key not set');
        }
    }

    public function test_name_key_invalid()
    {
        $json_body = [
            Users::USER_NAME => "John55",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        try {
            $validator->validateKeys($json_body);
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME][0], 'Invalid name. It can only consist of letters and can not be empty');
        }
    }

    public function test_surname_key_not_set()
    {
        $json_body = [
            Users::USER_NAME => "John",
            "surnameblablabla" => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        try {
            $validator->validateKeys($json_body);
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_SURNAME][0], 'surname key not set');
        }
    }

    public function test_surname_key_invalid()
    {
        $json_body = [
            Users::USER_NAME => "John",
            Users::USER_SURNAME => "Doe55"
        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        try {
            $validator->validateKeys($json_body);
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_SURNAME][0], 'Invalid surname. It can only consist of letters and can not be empty');
        }
    }

    public function test_name_key_empty()
    {
        $json_body = [
            Users::USER_NAME => "",
            Users::USER_SURNAME => "Doe"
        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        try {
            $validator->validateKeys($json_body);
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME][0], 'Invalid name. It can only consist of letters and can not be empty');
        }
    }

    public function test_surname_key_empty()
    {
        $json_body = [
            Users::USER_NAME => "John",
            Users::USER_SURNAME => ""
        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        try {
            $validator->validateKeys($json_body);
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_SURNAME][0], 'Invalid surname. It can only consist of letters and can not be empty');
        }
    }

    public function test_no_keys()
    {
        $json_body = [

        ];

        $errors = [];

        $validator = new ValidateUser(new AlphabeticStringValidator(), new ErrorsLoader());

        try {
            $validator->validateKeys($json_body);
        } catch (ValidateUserException $e){
            $errors = $validator->getErrors();
            $this->assertArrayHasKey(Users::USER_NAME, $errors);
            $this->assertArrayHasKey(Users::USER_SURNAME, $errors);
            $this->assertEquals($errors[Users::USER_NAME][0], 'name key not set');
            $this->assertEquals($errors[Users::USER_SURNAME][0], 'surname key not set');
        }
    }
}
