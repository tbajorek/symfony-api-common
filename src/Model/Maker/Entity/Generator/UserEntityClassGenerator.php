<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity\Generator;

use ApiCommon\Model\Maker\Entity\EntityClassGenerator;

class UserEntityClassGenerator extends EntityClassGenerator
{
    protected function getEntityTemplate(): string
    {
        return 'doctrine/UserEntity.tpl.php';
    }
}