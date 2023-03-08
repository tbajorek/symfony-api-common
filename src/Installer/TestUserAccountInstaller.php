<?php declare(strict_types=1);

namespace ApiCommon\Installer;

use ApiCommon\Installer\Config\ValueInstaller;
use ApiCommon\Model\Installer\DependentInstallerInterface;
use ApiCommon\Model\Installer\Entity\EntityInstaller;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\Entity\User\UserDataInstaller;
use ApiCommon\Model\Installer\Entity\User\UserDataInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\Loader\YamlLoader;
use ApiCommon\Model\Installer\LoaderAwareInstaller;
use ApiCommon\Model\Installer\LoadingDataInstaller;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstaller;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface;

class TestUserAccountInstaller implements InstallerInterface, LoaderAwareInstaller, EntityInstallerInterface, DependentInstallerInterface, UserDataInstallerInterface, SharingEntitiesInstallerInterface
{
    use LoadingDataInstaller, EntityInstaller, UserDataInstaller, SharingEntitiesInstaller;

    protected function getDataFilePath(): string
    {
        return 'test_user.yaml';
    }

    public function getEntityName(): string
    {
        return '';
    }

    public function getLoaderType(): string
    {
        return YamlLoader::getType();
    }

    public function install(): void
    {
        $loadedData = $this->getLoader()->load($this->getDataFilePath());

        $hydratedUser = $this->hydrateEntity($loadedData['user'], 'User');
        $userEntity = $this->getUserWithHashedPassword($hydratedUser->getEntity());
        $this->persist($userEntity);
        $this->shareEntity($hydratedUser->getId(), $userEntity, 'User');

        $hydratedAdmin = $this->hydrateEntity($loadedData['admin'], 'User');
        $adminEntity = $this->getUserWithHashedPassword($hydratedAdmin->getEntity());
        $this->persist($adminEntity);
        $this->shareEntity($hydratedAdmin->getId(), $adminEntity, 'User');

        foreach ($loadedData['config'] as $configEntry) {
            $hydratedValue = $this->hydrateEntity($configEntry, 'Config\Value');
            $this->persist($hydratedValue->getEntity());
        }

        $this->flush();
    }

    public function getDependencies(): array
    {
        return [
            ValueInstaller::class
        ];
    }
}