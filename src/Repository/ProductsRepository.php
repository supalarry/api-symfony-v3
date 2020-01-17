<?php

namespace App\Repository;

use App\Entity\Products;
use App\Interfaces\IEntity;
use App\Interfaces\IProductsRepository;
use App\Interfaces\IRepository;
use App\UserIdValidator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository implements IProductsRepository
{
    private $em;
    private $userIdValidator;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, UserIdValidator $userIdValidator)
    {
        parent::__construct($registry, Products::class);
        $this->em = $em;
        $this->userIdValidator = $userIdValidator;
    }

    public function create(array $characteristics): IEntity
    {
        $newProduct = new Products();
        $newProduct->setOwnerId($characteristics[Products::PRODUCT_OWNER_ID]);
        $newProduct->setType($characteristics[Products::PRODUCT_TYPE]);
        $newProduct->setTitle($characteristics[Products::PRODUCT_TITLE]);
        $newProduct->setSku($characteristics[Products::PRODUCT_SKU]);
        $newProduct->setCost($characteristics[Products::PRODUCT_COST]);
        $this->em->persist($newProduct);
        $this->em->flush();
        return $newProduct;
    }

    public function getById(int $id_user, int $id)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            return $this->findOneBy([
                "id" => $id,
                "ownerId" => $id_user
            ]);
        }
        return (null);
    }

    public function getAll(int $id_user)
    {
        if ($this->userIdValidator->validate($id_user))
        {
            return $this->findBy([
                "ownerId" => $id_user
            ]);
        }
        return (null);
    }
}
