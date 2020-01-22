<?php

namespace App\Entity;

use App\Interfaces\IEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders implements IEntity
{
    const ORDER_SHIPPING_DATA = "ship_to_address";
    const ORDER_LINE_ITEMS = "line_items";
    const ORDER_INFO = "order_info";

    const ORDER_ID = "id";
    const ORDER_OWNER_NAME = "name";
    const ORDER_OWNER_SURNAME = "surname";
    const ORDER_STREET = "street";
    const ORDER_STATE = "state";
    const ORDER_ZIP = "zip";
    const ORDER_COUNTRY = "country";
    const ORDER_PHONE = "phone";
    const ORDER_PRODUCTION_COST = "production_cost";
    const ORDER_SHIPPING_COST = "shipping_cost";
    const ORDER_TOTAL_COST = "total_cost";

    const PRODUCT_ID = "id";
    const PRODUCT_QUANTITY = "quantity";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $zip;

    /**
     * @ORM\Column(type="integer")
     */
    private $productionCost;

    /**
     * @ORM\Column(type="integer")
     */
    private $shippingCost;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalCost;

    public function getId(): ?int
    {
        return $this->id;
    }

    /* for testing purposes, so that OrdersTestRepository can simulate creation of an order */
    public function setId(int $id): ?self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getProductionCost(): ?int
    {
        return $this->productionCost;
    }

    public function setProductionCost(?int $productionCost): self
    {
        $this->productionCost = $productionCost;

        return $this;
    }

    public function getShippingCost(): ?int
    {
        return $this->shippingCost;
    }

    public function setShippingCost(?int $shippingCost): self
    {
        $this->shippingCost = $shippingCost;

        return $this;
    }

    public function getTotalCost(): ?int
    {
        return $this->totalCost;
    }

    public function setTotalCost(?int $totalCost): self
    {
        $this->totalCost = $totalCost;

        return $this;
    }
}
