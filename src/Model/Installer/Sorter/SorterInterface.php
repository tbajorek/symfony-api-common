<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Sorter;

use ApiCommon\Model\Installer\InstallerInterface;

interface SorterInterface
{
    /**
     * @param InstallerInterface[] $installers
     * @return InstallerInterface[]
     */
    public function sort(array $installers): array;
}