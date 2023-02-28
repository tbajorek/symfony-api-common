<?php declare(strict_types=1);

namespace ApiCommon\Model;

class Configuration
{
    public function __construct(public array $data = [])
    {
    }

    public function getAppPrefix(): string
    {
        return trim($this->data['app_prefix'] ?? 'App', '\\');
    }
}