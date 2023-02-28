<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity;

use ApiCommon\Model\Configuration;

class ClassNameResolver
{
    public function __construct(private readonly Configuration $configuration)
    {
    }

    public function resolve(string $entityClassName): string
    {
        return trim($this->configuration->getAppPrefix(), '\\')
            . '\\Entity\\'
            . trim($entityClassName, '\\');
    }
}