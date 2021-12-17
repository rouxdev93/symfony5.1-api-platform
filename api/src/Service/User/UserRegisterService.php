<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistException;
use App\Service\Password\EncoderService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterService
{
    private UserManager $userManager;
    private EncoderService $encoderService;

    public function __construct(
        UserManager $userManager,
        EncoderService $encoderService
    ){
        $this->userManager = $userManager;
        $this->encoderService = $encoderService;
    }

    public function create(Request $request): User
    {
        $name = RequestService::getField($request, 'name');
        $email = RequestService::getField($request, 'email');
        $password = RequestService::getField($request, 'password');

        if($user = $this->userManager->findOneByEmailOrFail($email)){
            throw UserAlreadyExistException::fromEmail($email);
        }

        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));

        $this->userManager->saveEntity($user);

        return $user;
    }
}
