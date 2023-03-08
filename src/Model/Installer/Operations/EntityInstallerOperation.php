<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Operations;

use ApiCommon\Model\Configuration;
use ApiCommon\Model\Installer\Entity\EntityHydrator;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use Doctrine\Persistence\ObjectManager;
use \ApiCommon\Exception\Installer\InstallerException;

class EntityInstallerOperation implements InstallerOperationInterface
{
    public function __construct(
        private readonly EntityHydrator $entityHydrator,
        private readonly Configuration $configuration,
        private readonly ObjectManager $objectManager,
    ) {
    }

    public function execute(InstallerInterface $installer): void
    {
        if ($installer instanceof EntityInstallerInterface) {
            $installer->setDependencies($this->entityHydrator, $this->configuration, $this->objectManager);
            $entityClass = $installer->getEntityClass();
            if ($installer->getEntityName() && !class_exists($entityClass)) {
                throw new InstallerException(
                    sprintf('Installation of % failed because requested entity class %s does not exist',
                        $installer::class,
                        $entityClass
                    )
                );
            }
        }
    }
}