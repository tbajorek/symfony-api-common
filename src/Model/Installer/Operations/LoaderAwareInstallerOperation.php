<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Operations;

use ApiCommon\Model\Installer\Entity\EntityHydrator;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\InstallersCollection;
use ApiCommon\Model\Installer\Loader\DataLocationLoader;
use ApiCommon\Model\Installer\Loader\LoaderProvider;
use ApiCommon\Model\Installer\LoaderAwareInstaller;

class LoaderAwareInstallerOperation implements InstallerOperationInterface
{
    public function __construct(
        private readonly LoaderProvider $loaderProvider
    ) {
    }

    public function execute(InstallerInterface $installer): void
    {
        if ($installer instanceof LoaderAwareInstaller) {
            $loader = $this->loaderProvider->get($installer->getLoaderType());
            if ($loader instanceof DataLocationLoader) {
                $loader->setInstaller($installer);
            }
            $installer->setLoader($loader);
        }
    }
}