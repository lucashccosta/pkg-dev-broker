<?php

declare(strict_types=1);

namespace Dev\Broker\Contracts;

use Dev\Broker\Entities\Response;
use Dev\Broker\Exceptions\ConsumeException;
use Dev\Broker\Exceptions\ProduceException;

interface IBroker
{   
    /**
     * @param array $payload
     * @throws ProduceException
     * @return null|Response
     */
    public function produce(array $payload): ?Response;

    /**
     * @param null|callable $callback
     * @throws ConsumeException
     * @return null|Response
     */
    public function consume(?callable $callback = null): ?Response;
}
