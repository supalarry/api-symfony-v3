<?php

namespace App\Interfaces;

interface IUsersRepository
{
    public function create(array $characteristics): IEntity;
}