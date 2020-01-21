<?php


namespace App;


use App\Interfaces\IProductsRepository;
use App\Interfaces\IReturn;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateOrder
{
    private $converter;
    private $repository;
    private $userIdValidator;
    private $validator;
    private $calculator;
    private $manager;

    public function __construct(JsonToArray $converter, IOrdersRepository $repository, UserIdValidator $userIdValidator, OrderValidator $validator, CostCalculator $calculator, FundManager $manager)
    {
        $this->converter = $converter;
        $this->repository = $repository;
        $this->userIdValidator = $userIdValidator;
        $this->validator = $validator;
        $this->calculator = $calculator;
        $this->manager = $manager;
    }

    public function handle(int $id_user): IReturn
    {

    }
}