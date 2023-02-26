<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\FrontendModel;

class SelectModel extends AbstractChoiceModel
{
    public function getType(): string
    {
        return 'select';
    }
}