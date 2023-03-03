<?php declare(strict_types=1);

namespace ApiCommon\Installer\Config;

use ApiCommon\Model\Installer\DependentInstallerInterface;

class ValueInstaller implements DependentInstallerInterface
{
    public function install(): void
    {
        echo self::class . PHP_EOL;
    }

    public function getDependencies(): array
    {
        return [
            ConfigGroupInstaller::class,
            DefinitionInstaller::class,
        ];
    }
}