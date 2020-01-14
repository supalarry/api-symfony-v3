<?php

namespace App\Interfaces;

interface IRepository
{
    public function save(IEntity $entity);
}