<?php

declare(strict_types=1);

namespace Dev\Broker\Exceptions;

use Exception;

class ConsumeException extends Exception
{
    const DEFAULT_MESSAGE = 'Consume message error.';
    const DEFAULT_CODE = 1000;

    public function __construct(?string $message = null, ?int $code = null) 
    {
        parent::__construct(
            message: $message ?? self::DEFAULT_MESSAGE,
            code: $code ?? self::DEFAULT_CODE
        );
    }
}
