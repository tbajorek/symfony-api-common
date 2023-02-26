<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\FrontendModel;

class OptionModel extends AbstractChoiceModel
{
    public function getType(): string
    {
        return $this->isMultiple() ? 'checkbox' : 'radio';
    }
}