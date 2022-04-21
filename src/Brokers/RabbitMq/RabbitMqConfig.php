<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers\Common;

use Dev\Broker\Brokers\BaseConfig;
use Dev\Broker\Brokers\RabbitMq\Config\ConsumerConfig;
use Dev\Broker\Brokers\RabbitMq\Config\QueueConfig;
use Dev\Broker\Brokers\RabbitMq\Config\ServerConfig;

class RabbitMqConfig extends BaseConfig
{
    protected ServerConfig $server;
    protected QueueConfig $queue;
    protected ConsumerConfig $consumer;

    public function getServer(): ServerConfig
    {
        return $this->server;
    }

    public function getQueue(): QueueConfig
    {
        return $this->queue;
    }

    public function getConsumer(): ConsumerConfig
    {
        return $this->consumer;
    }

    protected function config(): array
    {
        return [
            'server',
            'queue'
        ];
    }   

    protected function build(array $config): void
    {
        $this->server = new ServerConfig(
            host: $config['server']['host'],
            port: $config['server']['port'],
            user: $config['server']['user'],
            pass: $config['server']['pass'],
            vHost: $config['server']['vhost'],
            queue: $config['server']['queue'],
        );

            
        $this->queue = new QueueConfig(
            queueName: $config['queue']['name'],
            queueDlq: $config['queue']['dlq'],
            passive: $config['queue']['passive'] ?? false,
            durable: $config['queue']['durable'] ?? true,
            exclusive: $config['queue']['exclusive'] ?? false,
            autoDelete: $config['queue']['auto_delete'] ?? false,
            noWait: $config['queue']['no_wait'] ?? false
        );

        $this->consumer = new ConsumerConfig(
            noLocal: $config['consumer']['no_local'] ?? false,
            noAck: $config['consumer']['no_ack'] ?? true,
            exclusive: $config['consumer']['exclusive'] ?? false,
            noWait: $config['consumer']['no_wait'] ?? false
        );
    }
}
