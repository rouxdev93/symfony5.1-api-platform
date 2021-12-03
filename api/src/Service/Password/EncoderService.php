<?php

declare(strict_types=1);

namespace App\Service\Password;

use App\Entity\User;
use App\Exception\Password\PasswordException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class EncoderService
 * @package App\Service\Password
 */
class EncoderService
{
    private const MINIMUM_LENGTH = 6;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * EncoderService constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param UserInterface $user
     * @param string $password
     * @return string
     */
    public function generateEncodedPassword(UserInterface $user, string $password)
    {
        if (self::MINIMUM_LENGTH > \strlen($password)) {
            throw PasswordException::invalidLength();
        }

        return $this->userPasswordEncoder->encodePassword($user, $password);
    }

    /**
     * @param User $user
     * @param string $oldPassword
     * @return bool
     */
    public function isValidPassword(User $user, string $oldPassword): bool
    {
        return $this->userPasswordEncoder->isPasswordValid($user, $oldPassword);
    }
}