<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class UserRegisterService
{
    private EncoderService $encoderService;
    private UserRepository $userRepository;
    private MessageBusInterface $messageBus;

    public function __construct(
        EncoderService $encoderService,
        UserRepository $userRepository,
        MessageBusInterface $messageBus
    ){
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
        $this->messageBus = $messageBus;
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

        $this->messageBus->dispatch(
            new UserRegisteredMessage($user->getId(), $user->getName(), $user->getEmail(), $user->getToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
        return $user;
    }
}
