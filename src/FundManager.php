<?php


namespace App;


use App\Exception\FundManagerException;
use App\Interfaces\IUsersRepository;

class FundManager
{
    private $repository;

    public function __construct(IUsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function userPay(int $id_user, int $amount): void
    {
        if ($this->sufficientFunds($id_user, $amount))
            $this->subtractBalance($id_user, $amount);
        else
            throw new FundManagerException(["balance" => "not sufficient funds"]);
    }

    private function sufficientFunds(int $id_user, int $amount): bool
    {
        $user = $this->repository->getById($id_user);
        if ($user->getBalance() >= $amount)
            return (true);
        return (false);
    }

    private function subtractBalance(int $id_user, int $amount)
    {
        $user = $this->repository->getById($id_user);
        $user->subtractBalance($amount);
    }


}