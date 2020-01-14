<?php


namespace App\Repository;

use App\Interfaces\IRepository;
use App\Interfaces\IEntity;

class UsersTestRepository implements IRepository
{
    private $db;
    private $id;

    public function __construct()
    {
        $this->db = [];
        $this->id = 1;
    }

    public function save(IEntity $newUser): void
    {
        $db[$this->id] = $newUser;
        $this->id++;
    }

    public function getById(int $id)
    {
        return $this->db[$id];
    }
}