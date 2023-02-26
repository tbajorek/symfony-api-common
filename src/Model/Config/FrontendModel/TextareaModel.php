<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\FrontendModel;

class TextareaModel extends AbstractModel
{
    public function getType(): string
    {
        return 'textarea';
    }
}