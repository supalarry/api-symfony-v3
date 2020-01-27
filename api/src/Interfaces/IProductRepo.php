<?php

namespace App\Interfaces;
use App\Interfaces\IEntity;

interface IProductRepo
{
    public function create(int $id_owner, array $characteristics): IEntity;
}