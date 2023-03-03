<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Sorter;

use ApiCommon\Exception\Installer\CircularReferenceException;
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
    public function sort(array $installers): array
    {
        $stringSort = $this->stringSortFactory->create();
        $stringSort->setCircularInterceptor([$this, 'throwCircularDependency']);
        $mappedInstallers = [];
        foreach ($installers as $installer) {
            $installerClass = $installer::class;
            $mappedInstallers[$installerClass] = $installer;
            $stringSort->add(
                $installerClass,
                $installer instanceof DependentInstallerInterface ? $installer->getDependencies() : []
            );
        }
        $sortedClassNames = $stringSort->sort();
        return array_map(static function (string $installerClass) use ($mappedInstallers): InstallerInterface {
            return $mappedInstallers[$installerClass];
        }, $sortedClassNames);
    }

    /**
     * @internal
     */
    public function throwCircularDependency(): void
    {
        throw new CircularReferenceException('There is a circular dependency detected between some installers');
    }
}