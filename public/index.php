<?php

use Rinha\Config\DependencyInjection;
use Rinha\Controllers\ExtractController;
use Rinha\Controllers\TransactionController;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new DependencyInjection();

$app = new FrameworkX\App($container->getContainer());

$app->post('/clientes/{id:\d+}/transacoes', TransactionController::class);
$app->get('/clientes/{id:\d+}/extrato', ExtractController::class);

$app->run();
