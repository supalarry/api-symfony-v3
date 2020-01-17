<?php

namespace App\Entity;

use App\Interfaces\IEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 */
class Products implements IEntity
{
    const PRODUCT_ID = "id";
    const PRODUCT_OWNER_ID = "ownerId";
    const PRODUCT_TYPE = "type";
    const PRODUCT_TITLE = "title";
    const PRODUCT_SKU = "sku";
    const PRODUCT_COST = "cost";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ownerId;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $sku;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    public function getId(): ?int
    {
        return $this->id;
    }

    /* for testing purposes, so that UsersTestRepository can simulate creation of an user */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function setOwnerId(int $ownerId): self
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }
}
