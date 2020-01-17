<?php


namespace App;


use App\Interfaces\IReturn;

class ReturnProduct implements IReturn
{
    private $id;
    private $owner_id;
    private $type;
    private $title;
    private $sku;
    private $cost;

    public function __construct(int $id, int $owner_id, string $type, string $title, string $sku, int $cost)
    {
        $this->id = $id;
        $this->owner_id = $owner_id;
        $this->type = $type;
        $this->title = $title;
        $this->sku = $sku;
        $this->cost = $cost;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOwnerId()
    {
        return $this->owner_id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getCost()
    {
        return $this->cost;
    }
}