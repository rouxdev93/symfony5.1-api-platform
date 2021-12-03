<?php

declare(strict_types=1);

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class PasswordException
 * @package App\Exception\Password
 */
class PasswordException extends BadRequestHttpException
{
    /**
     * @return static
     */
    public static function invalidLength(): self
    {
        throw new self('Password must be at least 6 characters');
    }
}