<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Model\Installer\Loader\LoaderInterface;

interface LoaderAwareInstaller
{
    public function getLoaderType(): string;

    public function setLoader(LoaderInterface $loader): void;

    public function getLoader(): LoaderInterface;
}