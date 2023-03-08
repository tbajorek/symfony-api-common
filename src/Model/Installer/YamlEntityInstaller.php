<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Model\Installer\Entity\EntityInstaller;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\Loader\YamlLoader;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstaller;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface;

abstract class YamlEntityInstaller implements InstallerInterface, LoaderAwareInstaller, EntityInstallerInterface, SharingEntitiesInstallerInterface, DependentInstallerInterface
{
    use LoadingDataInstaller, EntityInstaller, SharingEntitiesInstaller;

    private string $entityName = '';

    abstract protected function getDataFilePath(): string;

    public function getLoaderType(): string
    {
        return YamlLoader::getType();
    }

    public function install(): void
    {
        $loadedData = $this->getLoader()->load($this->getDataFilePath());
        $this->setEntityName($loadedData['entityName']);

        foreach ($loadedData['data'] as $dataEntry) {
            $hydratedValue = $this->hydrate($dataEntry);
            $this->persist($hydratedValue->getEntity());
            if ($this->getSharingMode()) {
                $this->shareEntity($hydratedValue->getId(), $hydratedValue->getEntity());
            }
        }
        $this->flush();
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    protected function setEntityName(string $entityName): void
    {
        $this->entityName = $entityName;
        $entityClass = $this->getEntityClass();
        if (!class_exists($entityClass)) {
            throw new InstallerException(
                sprintf('Installation of % failed because requested entity class %s does not exist',
                    $installer::class,
                    $entityClass
                )
            );
        }
    }
}