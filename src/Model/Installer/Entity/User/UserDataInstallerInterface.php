<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Entity\User;

use ApiCommon\Entity\EntityInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserDataInstallerInterface
{
    public function setPasswordHasher(UserPasswordHasherInterface $passwordHasher): void;

    public function getUserWithHashedPassword(EntityInterface|UserInterface|PasswordAuthenticatedUserInterface $user): EntityInterface|UserInterface|PasswordAuthenticatedUserInterface;
}