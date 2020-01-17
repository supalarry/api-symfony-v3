<?php


namespace App\Repository;

use App\Entity\Users;
use App\Interfaces\IEntity;
use App\Interfaces\IUsersRepository;

class UsersTestRepository implements IUsersRepository
{
    private $db;
    private $id;

    public function __construct()
    {
        $this->db = [];
        $this->id = 1;
        $this->create([
            Users::USER_NAME => "John",
            Users::USER_SURNAME => "Doe"
        ]);
    }

    public function create(array $characteristics): IEntity
    {
        $newUser = new Users();
        $newUser->setId($this->id);
        $newUser->setName($characteristics[Users::USER_NAME]);
        $newUser->setSurname($characteristics[Users::USER_SURNAME]);
        $newUser->setBalance(10000);
        $this->db[$this->id] = $newUser;
        $this->id++;
        return $newUser;
    }

    public function getById(int $id)
    {
        if (array_key_exists($id, $this->db))
            return $this->db[$id];
        return null;
    }

    public function getAll(): array
    {
        return array_values($this->db);
    }
}