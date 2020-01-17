<?php


namespace App;

use App\Interfaces\IHandle;
use App\Interfaces\IReturn;
use App\Interfaces\IUsersRepository;
use Doctrine\ORM\ORMException;
use App\Exception\JsonToArrayException;
use App\Exception\ValidateUserException;
use App\Exception\CreateUserServiceException;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateUser
{
    private $request;
    private $repository;

    public function __construct(RequestStack $requestStack, IUsersRepository $repository)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repository = $repository;
    }

    public function handle(): IReturn
    {
        try {
            /* get json data */
            $converter = new JsonToArray($this->request);
            $dataArray = $converter->retrieve();
            /* validate data */
            $validateUser = new ValidateUser($dataArray, new AlphabeticStringValidator(), new ErrorsLoader());
            $validateUser->validateKeys();
            /* create user */
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
        /* return created user object for JSON response body */
        return new ReturnUser(
            $newUser->getId(),
            $newUser->getName(),
            $newUser->getSurname(),
            $newUser->getBalance()
        );
    }
}