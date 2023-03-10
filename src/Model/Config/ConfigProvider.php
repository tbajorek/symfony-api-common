<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\Provider;

use ApiCommon\Model\Config\ValueObject\ConfigValue;
use ApiCommon\Model\Config\ValueObject\WritableConfigValue;
use Symfony\Component\Uid\Uuid;

class ConfigProvider
{
    public function getConfig(string $path, ?string $scope = null, ?Uuid $scopeId = null): ConfigValue
    {

    }

    public function getWritableConfig(string $path, ?string $scope = null, ?Uuid $scopeId = null): WritableConfigValue
    {

    }

    public function isSetFlag(string $path, ?string $scope = null, ?Uuid $scopeId = null): bool
    {
        return $this->getConfig($path, $scope, $scopeId)->getValue();
    }
}