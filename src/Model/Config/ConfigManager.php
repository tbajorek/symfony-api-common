<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\Provider;

use ApiCommon\Model\Config\ValueObject\ConfigValue;
use ApiCommon\Model\Config\ValueObject\WritableConfigValue;
use Symfony\Component\Uid\Uuid;

class ConfigManager
{
    public function saveConfig(WritableConfigValue $configValue): void
    {

    }

    public function saveConfigs(array $configValues): void
    {
        foreach ($configValues as $configValue) {
            $this->saveConfig($configValue);
        }
    }
}