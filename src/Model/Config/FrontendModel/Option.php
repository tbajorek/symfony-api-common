<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\FrontendModel;

class Option
{
    public function __construct(
        public string $key,
        public string $value
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}