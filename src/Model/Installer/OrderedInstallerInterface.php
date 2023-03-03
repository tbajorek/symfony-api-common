<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

interface OrderedInstallerInterface extends InstallerInterface
{
    public function getOrder(): int;
}