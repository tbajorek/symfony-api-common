<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker;

use ApiCommon\Model\DataObject;
use Doctrine\DBAL\Types\Types;

/**
 * @property bool $nullable
 * @property string $type
 * @property string $fieldName
 */
class EntityField extends DataObject
{
    public function __construct(
        string $name,
        string $type = Types::STRING,
        bool $nullable = false,
        array $data = []
    ) {
        parent::__construct($data);
        $this->fieldName = $name;
        $this->type = $type;
        $this->nullable = $nullable;
    }

    public function getName(): string
    {
        return $this->fieldName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
