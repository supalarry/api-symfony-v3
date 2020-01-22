<?php


namespace App;


use App\Interfaces\IReturn;

class ReturnOrder implements IReturn
{
    private $id;
    private $name;
    private $surname;
    private $street;
    private $state;
    private $zip;
    private $country;
    private $phone;
}