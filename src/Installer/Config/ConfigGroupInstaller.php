<?php declare(strict_types=1);

namespace ApiCommon\Installer\Config;

use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\LoadingDataInstaller;
use ApiCommon\Model\Installer\LoaderAwareInstaller;

class ConfigGroupInstaller implements InstallerInterface, LoaderAwareInstaller
{
    use LoadingDataInstaller;

    public function getLoaderType(): string
    {
        return 'yaml';
    }

    public function install(): void
    {
        $groupsData = $this->getLoader()->load('config/groups.yaml');
        var_dump($groupsData);
        echo self::class . PHP_EOL;
    }
}