<?php

use Rinha\Controllers\ClienteController;
use Rinha\Controllers\ExtractController;
use Rinha\Controllers\TransactionController;
use Rinha\Repositories\ClienteRepository;

require_once __DIR__ . '/../vendor/autoload.php';


$credentials = 'test:test@localhost/rinha';
$db = (new React\MySQL\Factory())->createLazyConnection($credentials);
$repository = new ClienteRepository($db);

$app = new FrameworkX\App();

$app->get('/clientes/{id:\d+}', new ClienteController($repository));
$app->post('/clientes/{id:\d+}/transacoes', TransactionController::class);
$app->get('/clientes/{id:\d+}/extrato', ExtractController::class);

$app->run();
