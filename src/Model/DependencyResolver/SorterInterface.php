<?php declare(strict_types=1);

namespace ApiCommon\Model\DependencyResolver;

interface SorterInterface
{
    /**
     * @param object[] $objects
     * @return object[]
     */
    public function sort(array $objects): array;
}