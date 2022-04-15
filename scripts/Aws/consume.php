<?php

declare(strict_types=1);

use Dev\Broker\Facades\BrokerFacade;

require_once __DIR__ . '/../../vendor/autoload.php';

$config = [
    'access_key' => 'ACCESS_KEY',
    'secret_key' => 'SECRET_KEY',
    'region' => 'REGION',
    'queue' => 'QUEUE_URL',
];


$payload = [
    'time' => time(),
    'message' => rand(1, 999999)
];

$sqs = BrokerFacade::buildAwsSqs($config);

do {
    $consumed = $sqs->consume();
    var_dump($consumed);
} while (true);

die();
