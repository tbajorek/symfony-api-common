<?php declare(strict_types=1);

namespace ApiCommon\Model\DependencyResolver;

use MJS\TopSort\Implementations\StringSort;

class StringSortFactory
{
    public function create(): StringSort
    {
        return new StringSort();
    }
}