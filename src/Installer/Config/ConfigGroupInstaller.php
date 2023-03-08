<?php declare(strict_types=1);

namespace ApiCommon\Installer\Config;

use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\LoaderAwareInstaller;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface;
use ApiCommon\Model\Installer\YamlEntityInstaller;

class ConfigGroupInstaller extends YamlEntityInstaller implements InstallerInterface, LoaderAwareInstaller, EntityInstallerInterface, SharingEntitiesInstallerInterface
{
    protected function getDataFilePath(): string
    {
        return 'config/groups.yaml';
    }
}