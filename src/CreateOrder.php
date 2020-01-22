<?php


namespace App;

use App\Entity\Orders;
use App\Exception\CreateOrderServiceException;
use App\Exception\FundManagerException;
use App\Exception\JsonToArrayException;
use App\Exception\OrderValidatorException;
use App\Interfaces\IOrdersProductsRelationRepository;
use App\Interfaces\IOrdersRepository;
use App\Interfaces\IReturn;

class CreateOrder
{
    private $converter;
    private $repository;
    private $userIdValidator;
    private $validator;
    private $calculator;
    private $manager;
    private $transformer;
    private $relationRepository;

    public function __construct(JsonToArray $converter, IOrdersRepository $repository, IOrdersProductsRelationRepository $relationRepository, UserIdValidator $userIdValidator, OrderValidator $validator, CostCalculator $calculator, FundManager $manager, OrderTransformer $transformer)
    {
        $this->converter = $converter;
        $this->repository = $repository;
        $this->relationRepository = $relationRepository;
        $this->userIdValidator = $userIdValidator;
        $this->validator = $validator;
        $this->calculator = $calculator;
        $this->manager = $manager;
        $this->transformer = $transformer;
    }

    public function handle(int $id_user)
    {
        if (!$this->userIdValidator->validate($id_user))
            throw new CreateOrderServiceException(["id" => "invalid user"]);

        try {
            $dataArray = $this->converter->retrieve();
            $this->validator->validate($id_user, $dataArray);
            $costs = $this->calculator->calculate($id_user, $dataArray);
            $this->manager->userPay($id_user, $costs["total_cost"]);
            $newOrder = $this->repository->create($dataArray, $costs);
            $this->relationRepository->create($newOrder->getId(), $dataArray[Orders::ORDER_LINE_ITEMS]);
        } catch (JsonToArrayException $e) {
            throw new CreateOrderServiceException($e->getErrors());
        } catch (OrderValidatorException $e){
            throw new CreateOrderServiceException($e->getErrors());
        } catch (FundManagerException $e){
            throw new CreateOrderServiceException($e->getErrors());
        }

        return $this->transformer->transform($newOrder, $dataArray);
    }
}