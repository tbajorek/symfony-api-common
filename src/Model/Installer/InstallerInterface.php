<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use Exception;

interface InstallerInterface
{
    /**
     * @throws Exception
     */
    public function install(): void;
}