<?php


namespace App;


use App\Interfaces\IEntity;

class BalanceValidator
{
    public function validate(IEntity $entity, int $amount): bool
    {
        if ($entity->getBalance() - $amount >= 0)
            return (true);
        return (false);
    }
}