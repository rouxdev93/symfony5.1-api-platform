<?php

declare(strict_types=1);

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Class UserAlreadyExistException
 * @package App\Exception\User
 */
class UserAlreadyExistException extends ConflictHttpException
{
    private const MESSAGE = 'User with email %s already exist';

    /**
     * @param string $email
     * @return static
     */
    public static function fromEmail(string $email): self
    {
        throw new self(\sprintf(self::MESSAGE, $email));
    }
}