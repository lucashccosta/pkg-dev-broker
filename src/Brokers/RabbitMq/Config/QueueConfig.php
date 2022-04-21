<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers\RabbitMq\Config;

final class QueueConfig
{
    public function __construct(
        protected string $queueName,
        protected string $queueDlq, 
        protected bool $passive = false,
        protected bool $durable = true,
        protected bool $exclusive = false,
        protected bool $autoDelete = false,
        protected bool $noWait = false
    ) {
    }

    public function getQueueName(): string
    {
        return $this->queueName;
    }

    public function getQueueDlq(): string
    {
        return $this->queueDlq;
    }

    public function getPassive(): bool
    {
        return $this->passive;
    }

    public function getDurable(): bool
    {
        return $this->durable;
    }

    public function getExclusive(): bool
    {
        return $this->exclusive;
    }

    public function getAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    public function getNoWait(): bool
    {
        return $this->noWait;
    }
}
