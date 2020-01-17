<?php

namespace App\Controller;

use App\CreateUser;
use App\Exception\CreateUserServiceException;
use App\Interfaces\IUsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="createUser", methods={"POST"})
     * @param CreateUser $createUserService
     * @return JsonResponse
     */
    public function createUser(CreateUser $createUserService): JsonResponse
    {
        try {
            $createdUser = $createUserService->handle();
            return $this->json($createdUser, Response::HTTP_CREATED);
        } catch (CreateUserServiceException $e){
            return $this->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{id}", name="getUser", methods={"GET"})
     * @param IUsersRepository $repository
     * @param int $id
     * @return JsonResponse|Response
     */

    public function getUserById(IUsersRepository $repository, int $id)
    {
        $user = $repository->getById($id);
        if ($user)
            return $this->json($user, Response::HTTP_OK);
        return new Response(null,Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/users", name="getUsers", methods={"GET"})
     * @param IUsersRepository $repository
     * @return JsonResponse
     */
    public function getUsers(IUsersRepository $repository): JsonResponse
    {
        $users =  $repository->getAll();
        return $this->json($users, Response::HTTP_OK);
    }
}
