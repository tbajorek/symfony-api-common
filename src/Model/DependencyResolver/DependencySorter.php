<?php declare(strict_types=1);

namespace ApiCommon\Model\DependencyResolver;

use ApiCommon\Model\Installer\DependentInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use MJS\TopSort\CircularDependencyException;
use MJS\TopSort\ElementNotFoundException;

class DependencySorter implements SorterInterface
{
    public function __construct(private readonly StringSortFactory $stringSortFactory)
    {
    }

    /**
     * @inheritdoc
     * @throws CircularDependencyException
     * @throws ElementNotFoundException
     */
    public function sort(array $objects): array
    {
        if ($objects === []) {
            return [];
        }
        $stringSort = $this->stringSortFactory->create();
        $stringSort->setCircularInterceptor([$this, 'throwCircularDependency']);
        $mappedObjects = [];
        foreach ($objects as $object) {
            $objectClass = $object::class;
            $mappedObjects[$objectClass] = $object;
            $stringSort->add(
                $objectClass,
                $object instanceof DependentClassInterface ? $object->getDependencies() : []
            );
        }
        $sortedClassNames = $stringSort->sort();
        return array_map(static function (string $objectClass) use ($mappedObjects): object {
            return $mappedObjects[$objectClass];
        }, $sortedClassNames);
    }

    /**
     * @internal
     */
    public function throwCircularDependency(): void
    {
        throw new CircularReferenceException('There is a circular dependency detected between some classes');
    }
}