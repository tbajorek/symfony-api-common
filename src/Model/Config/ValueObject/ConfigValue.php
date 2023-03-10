<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\ValueObject;

use JsonSerializable;

class ConfigValue implements JsonSerializable
{
    public function __construct(private readonly string $path, private readonly mixed $value)
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'path' => $this->path,
            'value' => $this->value
        ];
    }
}