<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\BackendModel;

use ApiCommon\Entity\Config\ConfigValue;

interface ModelInterface
{
    public function getValue(ConfigValue $valueObject): mixed;
}