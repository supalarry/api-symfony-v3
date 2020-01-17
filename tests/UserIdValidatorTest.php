<?php

namespace App\Tests;

use App\Repository\UsersTestRepository;
use App\UserIdValidator;
use PHPUnit\Framework\TestCase;

class UserIdValidatorTest extends TestCase
{
    public function test_valid_id()
    {
        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $valid = $userIdValidator->validate(1);
        $this->assertEquals($valid, true);
    }

    public function test_invalid_id()
    {
        $userIdValidator = new UserIdValidator(new UsersTestRepository());
        $valid = $userIdValidator->validate(99999);
        $this->assertEquals($valid, false);
    }
}
