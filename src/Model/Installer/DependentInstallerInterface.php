<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

interface DependentInstallerInterface extends InstallerInterface
{
    /**
     * @return string[]
     */
    public function getDependencies(): array;
}