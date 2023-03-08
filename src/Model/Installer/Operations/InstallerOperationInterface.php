<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Operations;

use ApiCommon\Model\Installer\InstallerInterface;

interface InstallerOperationInterface
{
    public function execute(InstallerInterface $installer): void;
}