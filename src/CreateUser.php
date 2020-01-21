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

    public function handle(): IReturn
    {
        try {
            $dataArray = $this->converter->retrieve();
            $this->userValidator->validateKeys($dataArray);
            $newUser = $this->repository->create([
                Users::USER_NAME => $dataArray[Users::USER_NAME],
                Users::USER_SURNAME => $dataArray[Users::USER_SURNAME]
            ]);
        } catch (JsonToArrayException $e) {
            throw new CreateUserServiceException($e->getErrors());
        } catch (ValidateUserException $e) {
            throw new CreateUserServiceException($e->getErrors());
        } catch (ORMException $e) {
            throw new CreateUserServiceException(array($e));
        }

        return new ReturnUser(
            $newUser->getId(),
            $newUser->getName(),
            $newUser->getSurname(),
            $newUser->getBalance()
        );
    }
}