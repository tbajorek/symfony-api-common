<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity;

use ApiCommon\Model\DataObject;
use Doctrine\DBAL\Types\Types;

/**
 * Objective representation of a property to have similar class like EntityRelation
 *
 * @property bool $nullable
 * @property string $type
 * @property string $fieldName
 * @property string $propertyType
 */
class EntityField extends DataObject
{
    public function __construct(
        string $name,
        string $type = Types::STRING,
        bool $nullable = false,
        array $data = [],
        ?string $propertyType = null,
        ?bool $blank = false
    ) {
        parent::__construct($data);
        $this->fieldName = $name;
        $this->type = $type;
        $this->nullable = $nullable;
        $this->propertyType = $propertyType;
        $this->blank = $blank;
    }

    public function getName(): string
    {
        return $this->fieldName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPropertyType(): string
    {
        return $this->propertyType;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function isBlank(): bool
    {
        return $this->nullable;
    }
}
