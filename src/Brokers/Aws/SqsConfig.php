<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers\Aws;

use Dev\Broker\Brokers\BaseConfig;

class SqsConfig extends BaseConfig
{
    const DEFAULT_VERSION = 'latest';
    const DEFAULT_WAIT_TIME_TO_FETCH = 10;

    protected string $accessKey;
    protected string $secretKey;
    protected string $region;
    protected string $queue;
    protected string $version;
    protected ?string $dlq;
    protected int $waitTimeToFetch;
    
    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDql(): ?string
    {
        return $this->dlq;
    }

    public function getWaitTimeToFetch(): int
    {
        return $this->waitTimeToFetch;
    }

    protected function config(): array
    {
        return [
            'access_key',
            'secret_key',
            'region',
            'queue'
        ];
    }

    protected function build(array $config): void
    {
        $this->accessKey = $config['access_key'];
        $this->secretKey = $config['secret_key'];
        $this->region = $config['region'];
        $this->queue = $config['queue'];
        $this->version = $config['version'] ?? self::DEFAULT_VERSION;
        $this->dlq = $config['dlq'] ?? null;
        $this->waitTimeToFetch = $config['wait_time_seconds'] ?? self::DEFAULT_WAIT_TIME_TO_FETCH;
    }
}
