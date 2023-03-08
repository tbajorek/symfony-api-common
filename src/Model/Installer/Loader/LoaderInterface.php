<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Loader;

use ApiCommon\Exception\Installer\LoadDataException;
use ApiCommon\Model\Installer\InstallerInterface;

interface LoaderInterface
{
    /**
     * @throws LoadDataException
     */
    public function load(string $filePath): mixed;

    public function setInstaller(InstallerInterface $installer): void;

    public static function getType(): string;
}