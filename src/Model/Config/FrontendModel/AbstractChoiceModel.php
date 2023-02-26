<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\FrontendModel;

abstract class AbstractChoiceModel extends AbstractModel
{
    public function isMultiple(): bool
    {
        return $this->metadata->multiple;
    }

    public function getOptions(): array
    {
        $options = $this->metadata->options ?? [];
        return array_map(static function (string $key, string $value) {
            return new Option($key, $value);
        }, array_keys($options), array_values($options));
    }
}