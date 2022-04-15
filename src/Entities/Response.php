<?php

declare(strict_types=1);

namespace Dev\Broker\Entities;

class Response
{
    public function __construct(
        protected int $statusCode,
        protected array $body = [],
        protected array $headers = []
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
