<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity;

use RuntimeException;

class DependencyManager
{
    private array $foundDependencies = [];

    public function __construct(private string $makerClassName)
    {
    }

    public function getDependencyForEntity(string $entityClass, array $dependencies): string
    {
        if (!isset($this->foundDependencies[$entityClass])) {
            if(call_user_func($this->makerClassName.'::getEntityClass') === $entityClass) {
                $this->foundDependencies[$entityClass] = $this->makerClassName;
            } else {
                foreach ($dependencies as $dependencyClass) {
                    if(call_user_func($dependencyClass.'::getEntityClass') === $entityClass) {
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