<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Operations;

use ApiCommon\Model\Configuration;
use ApiCommon\Model\Installer\Entity\EntityHydrator;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\Repository\SharedDataRepository;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface;
use Doctrine\Persistence\ObjectManager;

class SharingEntitiesInstallerOperation implements InstallerOperationInterface
{
    public function __construct(
        private readonly SharedDataRepository $sharedDataRepository
    ) {
    }

    public function execute(InstallerInterface $installer): void
    {
        if ($installer instanceof SharingEntitiesInstallerInterface) {
            $installer->setSharedDataRepository($this->sharedDataRepository);
        }
    }
}