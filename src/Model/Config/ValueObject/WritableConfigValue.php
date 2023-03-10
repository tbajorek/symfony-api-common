<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\ValueObject;

use Symfony\Component\Uid\Uuid;

class WritableConfigValue extends ConfigValue
{
    public function __construct(private Uuid $valueId, string $path, mixed $value)
    {
        parent::__construct($path, $value);
    }

    public function getValueId(): Uuid
    {
        return $this->valueId;
    }

    public function getNonWritable(): ConfigValue
    {
        return new ConfigValue($this->getPath(), $this->getValue());
    }

    public function jsonSerialize(): mixed
    {
        return [
            'valueId' => $this->valueId->toRfc4122(),
        ] + parent::jsonSerialize();
    }
}