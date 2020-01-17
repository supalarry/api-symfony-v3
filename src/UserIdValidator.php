<?php


namespace App;

use App\Interfaces\IUsersRepository;

class UserIdValidator
{
    private $repository;

    public function __construct(IUsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate(int $id): bool
    {
        if ($this->repository->getById($id) != null)
            return (true);
        return (false);
    }
}