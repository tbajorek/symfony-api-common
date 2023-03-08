<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Sorter;

use ApiCommon\Model\Configuration;
use Psr\Container\ContainerInterface;
use ApiCommon\Model\DependencyResolver\SorterInterface;

class SorterFactory
{
    public function __construct(private readonly Configuration $configuration, private readonly ContainerInterface $container)
    {
    }

    public function create(): SorterInterface
    {
        switch ($this->configuration->getInstallerSortMode()) {
            case Configuration::INSTALLER_SORT_MODE_ORDER:
                $serviceId = 'api_common.installer.sorter.order';
                break;
            case Configuration::INSTALLER_SORT_MODE_DEPENDENCIES:
            default:
                $serviceId = 'api_common.dependency_resolver.sorter.dependency';
                break;
        }
        return $this->container->get($serviceId);
    }
}