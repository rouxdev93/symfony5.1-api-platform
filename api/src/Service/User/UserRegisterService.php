<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistException;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterService
{
    private EncoderService $encoderService;
    private UserRepository $userRepository;

    public function __construct(
        EncoderService $encoderService,
        UserRepository $userRepository
    ){
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
    }

    public function create(Request $request): User
    {
        $name = RequestService::getField($request, 'name');
        $email = RequestService::getField($request, 'email');
        $password = RequestService::getField($request, 'password');

        /*if($this->userManager->findOneByEmailOrFail($email)){
            throw UserAlreadyExistException::fromEmail($email);
        }*/

        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));

        $this->userRepository->saveEntity($user);

        return $user;
    }
}
