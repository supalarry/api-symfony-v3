<?php


namespace App;


use App\Entity\Products;
use App\Entity\Users;
use App\Exception\CreateProductServiceException;
use App\Exception\JsonToArrayException;
use App\Exception\UserIdValidatorException;
use App\Exception\ValidateProductException;
use App\Interfaces\IProductsRepository;
use Doctrine\ORM\ORMException;

class CreateProduct
{
    private $converter;
    private $repository;
    private $userIdValidator;
    private $productValidator;

    public function __construct(JsonToArray $converter, IProductsRepository $repository, UserIdValidator $userIdValidator, ValidateProduct $productValidator)
    {
        $this->converter = $converter;
        $this->repository = $repository;
        $this->userIdValidator = $userIdValidator;
        $this->productValidator = $productValidator;
    }

    public function handle(int $id_user)
    {
        if (!$this->userIdValidator->validate($id_user))
            throw new UserIdValidatorException([Users::USER_ID => "invalid user"]);

        try {
            $dataArray = $this->converter->retrieve();
            $this->productValidator->validateKeys($dataArray, $id_user);
            $newProduct = $this->repository->create($id_user, $dataArray);
        } catch (JsonToArrayException $e) {
            throw new CreateProductServiceException($e->getErrors());
        } catch (ValidateProductException $e) {
            throw new CreateProductServiceException($e->getErrors());
        } catch (ORMException $e) {
            throw new CreateProductServiceException(array($e));
        }

        return $newProduct;
    }
}