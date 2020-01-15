<?php


namespace App;

use App\Interfaces\IHandle;
use App\Interfaces\IRepository;
use App\Interfaces\IReturn;
use Doctrine\ORM\ORMException;
use App\Exception\JsonToArrayException;
use App\Exception\ValidateUserException;
use App\Exception\CreateUserServiceException;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateUser implements IHandle
{
    private $request;
    private $repository;

    public function __construct(RequestStack $requestStack, IRepository $repository)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repository = $repository;
    }

    public function handle(): IReturn
    {
        /* get json data as an array AND validate it */
        try {
            /* get json data */
            $converter = new JsonToArray($this->request);
            $dataArray = $converter->retrieve();
            /* validate data */
            $validateUser = new ValidateUser($dataArray, new AlphabeticStringValidator(), new ErrorsLoader());
            $validateUser->validateKeys();
            /* create user */
            $newUser = new Users();
            $newUser->setName($dataArray[Users::USER_NAME]);
            $newUser->setSurname($dataArray[Users::USER_SURNAME]);
            $newUser->setBalance(10000);
            $this->repository->save($newUser);
        } catch (JsonToArrayException $e) {
            throw new CreateUserServiceException($e->getErrors());
        } catch (ValidateUserException $e) {
            throw new CreateUserServiceException($e->getErrors());
        } catch (ORMException $e) {
            throw new CreateUserServiceException(array($e));
        }
        /* return created user object for response body */
        return new ReturnUser(
            $newUser->getId(),
            $newUser->getName(),
            $newUser->getSurname(),
            $newUser->getBalance()
        );
    }
}