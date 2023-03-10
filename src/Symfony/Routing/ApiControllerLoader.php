<?php declare(strict_types=1);

namespace ApiCommon\Symfony\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class ApiControllerLoader extends Loader
{
    public function __construct(string $env = null)
    {
        parent::__construct($env);
    }

    public function load($resource, string $type = null): mixed
    {
        $routes = new RouteCollection();

        $resource = dirname(__DIR__, 2) . '/Resources/config/routes.yaml';
        $type = 'yaml';

        $importedRoutes = $this->import($resource, $type);

        $routes->addCollection($importedRoutes);

        return $routes;
    }

    public function supports(mixed $resource, string $type = null): bool
    {
        return 'api_common' === $type;
    }
}