<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers;

use Dev\Broker\Exceptions\InvalidArgumentException;

abstract class BaseConfig
{   
    const INVALID_ARGUMENT_ERROR_MESSAGE = 'Invalid config params.';

    public function __construct(array $config)
    {
        $this->validate($config);
    }

    protected abstract function config(): array;
    
    private function validate(array $config): void
    {
        if (!empty(array_diff(array_keys($config), $this->config()))) {
            throw new InvalidArgumentException(self::INVALID_ARGUMENT_ERROR_MESSAGE);
        }
    }
}
