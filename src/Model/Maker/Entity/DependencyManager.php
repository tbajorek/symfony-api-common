<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity;

use ApiCommon\Model\Configuration;
use RuntimeException;

/**
 * Manage Maker class' dependencies passed to a method. It should be instantiated for every maker separately,
 * instead of using object from DI.
 */
class DependencyManager
{
    private array $foundDependencies = [];

    public function __construct(private string $makerClassName, private ClassNameResolver $classNameResolver)
    {
    }

    public function getDependencyForEntity(string $entityClass, array $dependencies): string
    {
        if (!isset($this->foundDependencies[$entityClass])) {
            if($this->classNameResolver->resolve(call_user_func($this->makerClassName.'::getEntityClassName')) === $entityClass) {
                $this->foundDependencies[$entityClass] = $this->makerClassName;
            } else {
                foreach ($dependencies as $dependencyClass) {
                    if($this->classNameResolver->resolve(call_user_func($dependencyClass.'::getEntityClassName')) === $entityClass) {
                        $this->foundDependencies[$entityClass] = $dependencyClass;
                        break;
                    }
                }
            }
            if (!isset($this->foundDependencies[$entityClass])) {
                throw new RuntimeException('There is no configured dependency for ' . $entityClass);
            }
        }
        return $this->foundDependencies[$entityClass];
    }
}