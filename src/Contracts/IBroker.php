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
     * @return Response
     */
    public function produce(array $payload): Response;

    /**
     * @throws ConsumeException
     * @return Response
     */
    public function consume(): Response;
}
