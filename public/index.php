<?php

use Rinha\Config\DependencyInjection;
use React\Http\Message\Response;
use Rinha\Controllers\ExtractController;
use Rinha\Controllers\TransactionController;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$container = new DependencyInjection();

$app = new FrameworkX\App($container->getContainer());

$app->get('/', function () {
    return Response::plaintext(
        "Rinha com Async PHP!\n"
    );
});
$app->post('/clientes/{id:\d+}/transacoes', TransactionController::class);
$app->get('/clientes/{id:\d+}/extrato', ExtractController::class);

$app->run();
