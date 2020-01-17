<?php

namespace App\Tests;

use App\Entity\Users;
use App\BalanceValidator;
use PHPUnit\Framework\TestCase;

class BalanceValidatorTest extends TestCase
{
    public function test_sufficient_funds()
    {
        $user = new Users();
        $user->setBalance(10000);
        $orderPrice = 5000;
        $BalanceValidator = new BalanceValidator();
        $valid = $BalanceValidator->validate($user, $orderPrice);
        $this->assertEquals($valid, true);
    }

    public function test_funds_equals_expenses()
    {
        $user = new Users();
        $user->setBalance(10000);
        $orderPrice = 10000;
        $BalanceValidator = new BalanceValidator();
        $valid = $BalanceValidator->validate($user, $orderPrice);
        $this->assertEquals($valid, true);
    }

    public function test_insufficient_funds()
    {
        $user = new Users();
        $user->setBalance(10000);
        $orderPrice = 50000;
        $BalanceValidator = new BalanceValidator();
        $valid = $BalanceValidator->validate($user, $orderPrice);
        $this->assertEquals($valid, false);
    }
}
