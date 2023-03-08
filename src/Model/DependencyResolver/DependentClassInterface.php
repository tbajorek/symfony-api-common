<?php declare(strict_types=1);

namespace ApiCommon\Model\DependencyResolver;

interface DependentClassInterface
{
    /**
     * Return full names of classes which this current one depends on
     *
     * @return string[]
     */
    public function getDependencies(): array;
}