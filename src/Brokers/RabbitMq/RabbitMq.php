<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers\Common;

use Dev\Broker\Contracts\IBroker;
use Dev\Broker\Entities\Response;
use Dev\Broker\Exceptions\ConsumeException;
use Dev\Broker\Exceptions\ProduceException;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

class RabbitMq implements IBroker
{
    private RabbitMqConfig $config;
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function produce(array $payload): ?Response
    {
        try {
            $this->createConnection();
            
            $message = new AMQPMessage(
                body: json_encode($payload),
                properties: ['delivery_mode' => 2] // make message persistent
            );

            $this->channel->basic_publish(
                msg: $message,
                routing_key: $this->config->getQueue()->getQueueName()
            );

            return new Response(
                body: ['message' => $payload],
            );
        } catch (Exception $e) {
            throw new ProduceException(
                message: ProduceException::DEFAULT_MESSAGE . ': '. $e->getMessage()
            );
        } finally {
            $this->closeConnection();
        }
    }

    public function consume(?callable $callback = null): ?Response
    {
        try {
            $this->createConnection();

            if ($this->config->getConsumer()->getnoAck() === false) {
                $this->channel->basic_qos(null, 1, null);
            }

            $this->channel->basic_consume(
                queue: $this->config->getQueue()->getQueueName(),
                no_local: $this->config->getConsumer()->getNoLocal(),
                no_ack: $this->config->getConsumer()->getnoAck(),
                exclusive: $this->config->getConsumer()->getExclusive(),
                nowait: $this->config->getConsumer()->getNoWait(),
                callback: function (AMQPMessage $message) use ($callback) {
                    try {
                        call_user_func($callback, json_decode($message->body, true), $this->config->getQueue()->getQueueName());
                        // $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                        $this->channel->basic_ack($message->getDeliveryTag());
                    } catch (Throwable $e) {
                        $this->channel->basic_reject(
                            $message->getDeliveryTag(),
                            $this->config->getQueue()->getQueueDlq()
                        );
                    }
                }
            );

            while (count($this->channel->callbacks)) {
                $this->channel->wait();
            }

            return null;
        } catch (Exception $e) {
            throw new ConsumeException(
                message: ConsumeException::DEFAULT_MESSAGE . ': '. $e->getMessage()
            );
        } finally {
            $this->closeConnection();
        }
    }

    private function createConnection(): void
    {
        $this->connection = new AMQPStreamConnection(
            host: $this->config->getServer()->getHost(),
            port: $this->config->getServer()->getPort(),
            user: $this->config->getServer()->getUser(),
            password: $this->config->getServer()->getPass(),
            vhost: $this->config->getServer()->getVHost()
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(
            queue: $this->config->getQueue()->getQueueName(),
            passive: $this->config->getQueue()->getPassive(),
            durable: $this->config->getQueue()->getDurable(),
            exclusive: $this->config->getQueue()->getExclusive(),
            auto_delete: $this->config->getQueue()->getAutoDelete(),
            nowait: $this->config->getQueue()->getNoWait()
        );
    }

    private function closeConnection(): void
    {
        if ($this->channel->is_open()) {
            $this->channel->close();
        }
        
        if ($this->connection->isConnected()) {
            $this->connection->close();
        }
    }
}   
