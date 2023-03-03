<?php declare(strict_types=1);

namespace ApiCommon\DependencyInjection;

use ApiCommon\DependencyInjection\CompilerPass\InstallerLoadersCompilePass;
use ApiCommon\DependencyInjection\CompilerPass\InstallersCompilePass;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\Loader\LoaderInterface;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class ApiCommonExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $loader->load('maker.yaml');
        $loader->load('installer.yaml');

        $container->registerForAutoconfiguration(InstallerInterface::class)
            ->addTag(InstallersCompilePass::INSTALLER_TAG);

        $container->registerForAutoconfiguration(LoaderInterface::class)
            ->addTag(InstallerLoadersCompilePass::INSTALLER_LOADER_TAG);

        $container->setParameter('api_common.config.data', $config);
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass(new InstallersCompilePass());
    }
}