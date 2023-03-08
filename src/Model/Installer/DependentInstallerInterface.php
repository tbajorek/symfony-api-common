<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Model\DependencyResolver\DependentClassInterface;

interface DependentInstallerInterface extends DependentClassInterface
{
    /**
     * @return string[]
     */
    public function getDependencies(): array;
}