<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Loader;

use ApiCommon\Exception\Installer\LoaderNotRegisteredException;

class LoaderProvider
{
    private array $loaders = [];

    public function addLoader(LoaderInterface $loader): void
    {
        $this->loaders[$loader->getType()] = $loader;
    }

    public function get(string $loaderType): LoaderInterface
    {
        if (!array_key_exists($loaderType, $this->loaders)) {
            throw new LoaderNotRegisteredException(sprintf('Loader type %s is not registered', $loaderType));
        }
        return $this->loaders[$loaderType];
    }
}