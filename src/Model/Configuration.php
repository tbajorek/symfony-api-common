<?php declare(strict_types=1);

namespace ApiCommon\Model;

class Configuration
{
    public const INSTALLER_SORT_MODE_DEPENDENCIES = 'dependencies';
    public const INSTALLER_SORT_MODE_ORDER = 'order';

    public function __construct(public array $data = [])
    {
    }

    public function getAppPrefix(): string
    {
        return trim($this->data['app_prefix'] ?? 'App', '\\');
    }

    public function getInstallerSortMode(): string
    {
        return $this->data['installer']['sort_mode'] ?? 'dependencies';
    }
}