<?php declare(strict_types=1);

namespace ApiCommon\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class InstallerLoadersCompilePass implements CompilerPassInterface
{
    public const INSTALLER_LOADER_TAG = 'app.installer.loader';

    public function process(ContainerBuilder $container): void
    {
        $collectionDefinition = $container->getDefinition('api_common.installer.loader_provider');
        $taggedServices = $container->findTaggedServiceIds(self::INSTALLER_LOADER_TAG);

        foreach ($taggedServices as $serviceId => $tags) {
            $collectionDefinition->addMethodCall('addLoader', [new Reference($serviceId)]);
        }
    }
}