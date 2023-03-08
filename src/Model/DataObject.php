<?php declare(strict_types=1);

namespace ApiCommon\Model;

use JsonSerializable;

class DataObject implements JsonSerializable
{
    public function __construct(private array $data = [])
    {
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function getAllData(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}