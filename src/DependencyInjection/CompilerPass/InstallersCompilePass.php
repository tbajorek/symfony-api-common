<?php declare(strict_types=1);

namespace ApiCommon\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class InstallersCompilePass implements CompilerPassInterface
{
    public const INSTALLER_TAG = 'app.installer';

    public function process(ContainerBuilder $container): void
    {
        $collectionDefinition = $container->getDefinition('api_common.installer.collection');
        $taggedServices = $container->findTaggedServiceIds(self::INSTALLER_TAG);

        foreach ($taggedServices as $serviceId => $tags) {
            $collectionDefinition->addMethodCall('addInstaller', [new Reference($serviceId)]);
        }
    }
}