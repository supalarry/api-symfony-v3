<?php


namespace App;

use App\Interfaces\IReturn;
use App\Interfaces\IUsersRepository;
use Doctrine\ORM\ORMException;
use App\Exception\JsonToArrayException;
use App\Exception\ValidateUserException;
use App\Exception\CreateUserServiceException;
use App\Entity\Users;

class CreateUser
{
    private $converter;
    private $repository;
    private $userValidator;

    public function __construct(JsonToArray $converter, IUsersRepository $repository, ValidateUser $userValidator)
    {
        $this->converter = $converter;
        $this->repository = $repository;
        $this->userValidator = $userValidator;
    }

    public function handle()
    {
        try {
            $dataArray = $this->converter->retrieve();
            $this->userValidator->validateKeys($dataArray);
            $newUser = $this->repository->create($dataArray);
        } catch (JsonToArrayException $e) {
            throw new CreateUserServiceException($e->getErrors());
        } catch (ValidateUserException $e) {
            throw new CreateUserServiceException($e->getErrors());
        } catch (ORMException $e) {
            throw new CreateUserServiceException(array($e));
        }

        return $newUser;
    }
}