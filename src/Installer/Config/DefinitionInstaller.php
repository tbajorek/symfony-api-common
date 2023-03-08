<?php declare(strict_types=1);

namespace ApiCommon\Installer\Config;

use ApiCommon\Model\Installer\DependentInstallerInterface;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\LoaderAwareInstaller;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface;
use ApiCommon\Model\Installer\YamlEntityInstaller;

class DefinitionInstaller extends YamlEntityInstaller implements InstallerInterface, LoaderAwareInstaller, EntityInstallerInterface, SharingEntitiesInstallerInterface, DependentInstallerInterface
{
    protected function getDataFilePath(): string
    {
        return 'config/definitions.yaml';
    }

    public function getDependencies(): array
    {
        return [
            ConfigGroupInstaller::class
        ];
    }
}