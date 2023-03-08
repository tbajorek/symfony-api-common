<?php declare(strict_types=1);

namespace ApiCommon;

use ApiCommon\DependencyInjection\CompilerPass\InstallerLoadersCompilePass;
use ApiCommon\DependencyInjection\CompilerPass\InstallerOperationsCompilePass;
use ApiCommon\DependencyInjection\CompilerPass\InstallersCompilePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiCommonBundle extends Bundle
{
    public function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass(new InstallersCompilePass());
        $containerBuilder->addCompilerPass(new InstallerLoadersCompilePass());
    }
}