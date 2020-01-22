<?php

namespace App\Interfaces;
use App\Interfaces\IEntity;

interface IProductsRepository
{
    public function create(int $id_owner, array $characteristics): IEntity;
}