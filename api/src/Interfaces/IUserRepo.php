<?php

namespace App\Interfaces;

interface IUserRepo
{
    public function create(array $characteristics): IEntity;
}