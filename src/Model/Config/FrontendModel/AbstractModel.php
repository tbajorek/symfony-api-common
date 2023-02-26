<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\FrontendModel;

use ApiCommon\Model\Config\Metadata;

abstract class AbstractModel implements ModelInterface
{
    public function __construct(
        public Metadata $metadata
    ) {}

    public function isRequired(): bool
    {
        return $this->metadata->required;
    }
}