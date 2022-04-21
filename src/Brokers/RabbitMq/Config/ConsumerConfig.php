<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers\RabbitMq\Config;

final class ConsumerConfig
{
    public function __construct(
        protected bool $noLocal = false,
        protected bool $noAck = true,
        protected bool $exclusive = false,
        protected bool $noWait = false
    ) {
    }

    public function getNoLocal(): bool
    {
        return $this->noLocal;
    }

    public function getnoAck(): bool
    {
        return $this->noAck;
    }

    public function getExclusive(): bool
    {
        return $this->exclusive;
    }

    public function getNoWait(): bool
    {
        return $this->noWait;
    }
}
