<?php

declare(strict_types=1);

namespace Dev\Broker\Brokers\Aws;

use Aws\Sqs\SqsClient; 
use Dev\Broker\Contracts\IBroker;
use Dev\Broker\Entities\Response;
use Dev\Broker\Exceptions\ConsumeException;
use Dev\Broker\Exceptions\ProduceException;
use Exception;

class Sqs implements IBroker
{   
    private SqsConfig $config;
    private SqsClient $client;

    public function __construct(array $config)
    {
        $this->build($config);
    }

    public function consume(?callable $callback = null): ?Response
    {
        do {
            try {
                $body = [];
    
                $result = $this->client->receiveMessage([
                    'AttributeNames' => ['SentTimestamp'],
                    'MaxNumberOfMessages' => 1,
                    'MessageAttributeNames' => ['All'],
                    'QueueUrl' => $this->config->getQueue(),
                    'WaitTimeSeconds' => $this->config->getWaitTimeToFetch()
                ]);
    
                if (!empty($result->get('Messages'))) {
                    try {
                        $message = current($result->get('Messages'));
                        
                        $this->client->deleteMessage([
                            'QueueUrl' => $this->config->getQueue(),
                            'ReceiptHandle' => $message['ReceiptHandle']
                        ]);
                    } catch (Exception $e) {}
    
                    $body = ['message' => $message['Body']];
                }
    
                return new Response(
                    statusCode: (int) $result['@metadata']['statusCode'],
                    body: $body,
                    headers: $result['@metadata']['headers']
                );
            } catch (Exception $e) {
                throw new ConsumeException(
                    message: ConsumeException::DEFAULT_MESSAGE . ': '. $e->getMessage()
                );
            }
        } while (true);
    }

    public function produce(array $payload): ?Response
    {
        try {
            $message = json_encode($payload);

            $result = $this->client->sendMessage([
                'MessageBody' => $message,
                'QueueUrl' => $this->config->getQueue()
            ]);

            return new Response(
                statusCode: (int) $result['@metadata']['statusCode'],
                body: ['message' => $message],
                headers: $result['@metadata']['headers']
            );
        } catch (Exception $e) {
            throw new ProduceException(
                message: ProduceException::DEFAULT_MESSAGE . ': '. $e->getMessage()
            );
        }
    }

    private function build(array $config): void
    {
        $this->config = new SqsConfig($config);
        $this->client = new SqsClient([
            'version' => $this->config->getVersion(),
            'region' => $this->config->getRegion(),
            'credentials' => [
                'key' => $this->config->getAccessKey(),
                'secret' => $this->config->getSecretKey()
            ]
        ]);

        $this->setDlq();
    }

    private function setDlq(): void
    {
        if (!empty($this->config->getDql())) {
            $policy = ['deadLetterTargetArn' => $this->config->getDql()]; 

            $this->client->setQueueAttributes([
                'Attributes' => ['RedrivePolicy' => json_encode($policy)],
                'QueueUrl' => $this->config->getQueue()
            ]);
        }
    }
}