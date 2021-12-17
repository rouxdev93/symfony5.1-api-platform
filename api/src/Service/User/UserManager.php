<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\CustomEntityManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class UserManager extends CustomEntityManager
{
    public function __construct(
        EntityManagerInterface $entityManager,
        Connection $connection
    ){
        parent::__construct($entityManager, $connection);
    }

    protected static function entityClass(): string
    {
        return User::class;
    }

    /**
     * @param string $email
     * @return User
     */
    public function findOneByEmailOrFail(string $email): User
    {
        if (null === $user = $this->repository->findOneBy(['email' => $email])) {
            throw UserNotFoundException::fromEmail($email);
        }
        return $user;
    }
}