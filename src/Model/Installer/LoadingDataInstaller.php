<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Model\Installer\Loader\LoaderInterface;

trait LoadingDataInstaller
{
    private LoaderInterface $loader;

    public function setLoader(LoaderInterface $loader): void
    {
        $this->loader = $loader;
    }

    public function getLoader(): LoaderInterface
    {
        return $this->loader;
    }
}