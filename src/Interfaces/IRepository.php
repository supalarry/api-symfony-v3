<?php

namespace App\Interfaces;

interface IRepository
{
    public function create(array $characteristics): IEntity;
    public function getById(int $id);
    public function getAll(): array;
}