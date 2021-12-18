<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

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
}
