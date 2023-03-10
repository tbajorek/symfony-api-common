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

        $loader->load('services/config.yaml');
        $loader->load('services/dependency_resolver.yaml');
        $loader->load('services/entities.yaml');
        $loader->load('services/maker.yaml');
        $loader->load('services/installer.yaml');
        $loader->load('services/routing.yaml');

        $container->setParameter('api_common.config.data', $config);
    }
}