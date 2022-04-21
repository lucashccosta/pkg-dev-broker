<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers\RabbitMq\Config;

final class ServerConfig
{
    public function __construct(
        protected string $host,
        protected string $port,
        protected string $user,
        protected string $pass,
        protected string $vHost,
        protected string $queue
    ) {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPass(): string
    {
        return $this->pass;
    }

    public function getVHost(): string
    {
        return $this->vHost;
    }
}
