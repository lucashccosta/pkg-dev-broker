<?php

declare(strict_types=1);

namespace Dev\Broker\Facades;

use Dev\Broker\Brokers\Aws\Sqs;
use Dev\Broker\Contracts\IBroker;
use Dev\Broker\Exceptions\InvalidArgumentException;

final class BrokerFacade
{
    /**
     * @param array $config
     * @throws InvalidArgumentException
     * @return IBroker
     */
    public static function buildAwsSqs(array $config): IBroker
    {
        return new Sqs($config);
    }
}
