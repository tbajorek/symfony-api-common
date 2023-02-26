<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\BackendModel;

use ApiCommon\Entity\Config\ConfigValue;

class DatabaseValue implements ModelInterface
{
    public function getValue(ConfigValue $valueObject): ?string
    {
        return $valueObject->getValue();
    }
}