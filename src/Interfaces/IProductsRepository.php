<?php

namespace App\Interfaces;
use App\Interfaces\IEntity;

interface IProductsRepository
{
    public function create(array $characteristics): IEntity;
}