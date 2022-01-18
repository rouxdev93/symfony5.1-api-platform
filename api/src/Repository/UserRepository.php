<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return User::class ;
    }

    public function findOneByEmailOrFail(string $email): User
    {
        if (null === $user = $this->findOneBy(['email' => $email])) {
            throw UserNotFoundException::fromEmail($email);
        }
        return $user;
    }

    public function findOneInactiveByIdOrTokenOrFail(string $id, string $token): User
    {
        if (null === $user = $this->findOneBy(['id' => $id, 'token' => $token])) {
            throw UserNotFoundException::fromUserIdAndToken($id, $token);
        }
        return $user;
    }



}
