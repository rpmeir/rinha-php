<?php

namespace Rinha\Config;

use FrameworkX\Container;
use Rinha\Controllers\ClienteController;
use Rinha\Controllers\ExtractController;
use Rinha\Controllers\TransactionController;
use React\MySQL\ConnectionInterface;
use React\MySQL\Factory;
use Rinha\Repository\ClienteRepository;

class DependencyInjection {

    public function __construct() {
        // nao retorna nada
    }

    public static function getContainer (): Container {

        $credentials = 'test:test@localhost/rinha';
        $db = (new Factory())->createLazyConnection($credentials);

        $connections = [
            ConnectionInterface::class => function ($credentials) {
                return (new Factory())->createLazyConnection($credentials);
            }
        ];

        $controllers = [
            TransactionController::class => fn() => new TransactionController(),
            ExtractController::class => fn() => new ExtractController(),
            ClienteController::class => fn($db) => new ClienteController(new ClienteRepository($db))
        ];

        $interfaces = [

        ];

        return new Container(array_merge($controllers, $interfaces, $connections));
    }
}
