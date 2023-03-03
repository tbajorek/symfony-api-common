<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Exception\Installer\SharedValueNotSetException;

class SharedDataRepository
{
    private array $sharedData = [];

    public function add(string $id, mixed $value): void
    {
        $this->sharedData[$id] = $value;
    }

    public function get(string $id): mixed
    {
        if ($this->sharedData[$id]) {
            return $this->sharedData[$id];
        }
        throw new SharedValueNotSetException(sprintf('No shared value with identifier = %s', $id));
    }
}