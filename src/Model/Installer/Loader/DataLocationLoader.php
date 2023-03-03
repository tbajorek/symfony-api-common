<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Loader;

use ApiCommon\Exception\Installer\LoadDataException;
use ApiCommon\Model\Installer\InstallerInterface;

interface DataLocationLoader
{
    public function setInstaller(InstallerInterface $installer): void;
}