<?php declare(strict_types=1);

namespace ApiCommon\Exception\DependencyResolver;

use LogicException;

class CircularReferenceException extends LogicException
{

}