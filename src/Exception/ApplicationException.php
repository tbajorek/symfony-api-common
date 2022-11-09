<?php declare(strict_types=1);

namespace ApiCommon\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatableMessage;
use Throwable;

class ApplicationException extends Exception
{
    public const EXCEPTION_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    private TranslatableMessage $translatableMessage;

    public function __construct(
        TranslatableMessage $message,
        int $code = self::EXCEPTION_CODE,
        ?Throwable $previous = null
    ) {
        $this->translatableMessage = $message;
        parent::__construct($this->getRawMessage(), $code, $previous);
    }

    public function getRawMessage(): string
    {
        return $this->translatableMessage->getMessage();
    }

    public function getParameters(): array
    {
        return $this->translatableMessage->getParameters();
    }
}