<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Model\Installer\Loader\DataLocationLoader;
use ApiCommon\Model\Installer\Loader\LoaderProvider;
use Exception;

class InstallerRunner
{
    public function __construct(
        private readonly InstallersCollection $installersCollection,
        private readonly LoaderProvider $loaderProvider
    ) {
    }

    /**
     * @throws Exception
     */
    public function install(): void
    {
        foreach ($this->installersCollection->getInstallers() as $installer) {
            if ($installer instanceof LoaderAwareInstaller) {
                $loader = $this->loaderProvider->get($installer->getLoaderType());
                if ($loader instanceof DataLocationLoader) {
                    $loader->setInstaller($installer);
                }
                $installer->setLoader($loader);
            }
            $installer->install();
        }
    }
}