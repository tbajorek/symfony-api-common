<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Sorter;

use ApiCommon\Model\Installer\DependentInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\OrderedInstallerInterface;
use ApiCommon\Model\DependencyResolver\SorterInterface;

class OrderSorter implements SorterInterface
{
    /**
     * @param DependentInstallerInterface[] $objects
     * @return DependentInstallerInterface[]
     */
    public function sort(array $objects): array
    {
        usort($installers, static function (InstallerInterface $a, InstallerInterface $b): int {
            if ($a instanceof OrderedInstallerInterface && $b instanceof OrderedInstallerInterface) {
                return $a->getOrder() <=> $b->getOrder();
            }
            $result = 0;
            if ($a instanceof OrderedInstallerInterface) {
                $result = $a->getOrder() === 0 ? 0 : 1;
            }
            if ($b instanceof OrderedInstallerInterface) {
                $result = $b->getOrder() === 0 ? 0 : -1;
            }
            return $result;
        });
        return $installers;
    }
}