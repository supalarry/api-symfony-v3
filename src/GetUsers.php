<?php


namespace App;


use Doctrine\ORM\EntityManagerInterface;

class GetUsers
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getUsers(): array
    {
        return $this->em->getRepository($_ENV['UsersEntityPath'])->findAll();
    }
}