<?php

declare(strict_types=1);


namespace App\Service\User;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountService
{
    public function __construct(
        UserRepository $userRepository
    ){
        $this->userRepository = $userRepository;
    }

    public function activate(Request $request, string $id): User
    {
        $user = $this->userRepository->findOneInactiveByIdOrTokenOrFail(
            $id,
            RequestService::getField($request,'token')
        );

        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->saveEntity($user);

        return $user;
    }

}