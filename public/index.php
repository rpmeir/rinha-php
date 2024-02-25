<?php

use Rinha\Config\DependencyInjection;
use Rinha\Controllers\ClienteController;
use Rinha\Controllers\ExtractController;
use Rinha\Controllers\TransactionController;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$container = new DependencyInjection();

$app = new FrameworkX\App($container->getContainer());

$app->get('/', function () {
    return React\Http\Message\Response::plaintext("Rinha de Backend!\n");
});
$app->get('/clientes/{id:\d+}', ClienteController::class);
$app->post('/clientes/{id:\d+}/transacoes', TransactionController::class);
$app->get('/clientes/{id:\d+}/extrato', ExtractController::class);

$app->run();
