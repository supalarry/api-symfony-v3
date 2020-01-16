<?php

namespace App\Repository;

use App\Entity\Products;
use App\Interfaces\IEntity;
use App\Interfaces\IRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository implements IRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Products::class);
        $this->em = $em;
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
