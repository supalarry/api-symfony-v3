<?php

namespace App\Controller;

use App\CreateUser;
use App\Entity\Users;
use App\Exception\CreateUserServiceException;
use App\GetUsers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
            return $this->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{id}", name="getUser", methods={"GET"})
     * @ParamConverter("user", class="Users")
     * @param Users $user
     * @return JsonResponse
     */
    public function getUserById(Users $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK);
    }

    /**
     * @Route("/users", name="getUser", methods={"GET"})
     * @param GetUsers $getUsersService
     * @return JsonResponse
     */
    public function getUsers(GetUsers $getUsersService): JsonResponse
    {
        //$getUsersService = $this->get('GetUserService');
        $users = $getUsersService->getUsers();
        return $this->json($users, Response::HTTP_OK);
    }
}
