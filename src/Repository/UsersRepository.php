<?php

namespace App\Repository;

use App\Entity\Users;
use App\Interfaces\IUsersRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Interfaces\IEntity;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements IUsersRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Users::class);
        $this->em = $em;
    }

    public function create(array $characteristics): IEntity
    {
        $newUser = new Users();
        $newUser->setName($characteristics[Users::USER_NAME]);
        $newUser->setSurname($characteristics[Users::USER_SURNAME]);
        $newUser->setBalance(10000);
        $this->em->persist($newUser);
        $this->em->flush();
        return $newUser;
    }

    public function getById(int $id)
    {
        return $this->findOneBy([
            "id" => $id
        ]);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }
}
