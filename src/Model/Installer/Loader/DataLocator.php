<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Loader;

use ApiCommon\Exception\Installer\LoadDataException;
use ApiCommon\Model\Installer\InstallerInterface;
use ReflectionClass;

trait DataLocator
{
    private ?InstallerInterface $installer = null;

    public function setInstaller(InstallerInterface $installer): void
    {
        $this->installer = $installer;
    }

    protected function getFullFilePath(string $filename): string
    {
        if ($this->installer === null) {
            throw new LoadDataException(
                sprintf(
                    'File %s can not be located because an installer is not set in data locator service',
                    $filename
                )
            );
        }
        $installerReflection = new ReflectionClass($this->installer::class);
        $installerClassFile = $installerReflection->getFileName();
        $pathParts = explode(DIRECTORY_SEPARATOR . 'Installer' . DIRECTORY_SEPARATOR, $installerClassFile);
        if (count($pathParts) !== 2) {
            throw new LoadDataException(
                sprintf('Install data directory for installer %s can not be deducted', $this->installer::class)
            );
        }
        return implode(DIRECTORY_SEPARATOR, [
            $pathParts[0],
            'Resources',
            'install',
            trim($filename, DIRECTORY_SEPARATOR)
        ]);
    }
}