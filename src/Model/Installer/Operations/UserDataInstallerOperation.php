<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Operations;

use ApiCommon\Model\Configuration;
use ApiCommon\Model\Installer\Entity\EntityHydrator;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\Entity\User\UserDataInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use Doctrine\Persistence\ObjectManager;
use \ApiCommon\Exception\Installer\InstallerException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataInstallerOperation implements InstallerOperationInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function execute(InstallerInterface $installer): void
    {
        if ($installer instanceof UserDataInstallerInterface) {
            $installer->setPasswordHasher($this->passwordHasher);
        }
    }
}