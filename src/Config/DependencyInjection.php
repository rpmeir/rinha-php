<?php

namespace Rinha\Config;

use FrameworkX\Container;
use Rinha\Controllers\ExtractController;
use Rinha\Controllers\TransactionController;
use React\MySQL\ConnectionInterface;
use React\MySQL\Factory;

class DependencyInjection {

    public function __construct() {
        // nao retorna nada
    }

    public static function getContainer (): Container {
        return new Container([
            TransactionController::class => fn() => new TransactionController(),
            ExtractController::class => fn() => new ExtractController(),
            ConnectionInterface::class => function () {
                $credentials = 'alice:secret@localhost/bookstore';
                return (new Factory())->createLazyConnection($credentials);
            }
        ]);
    }
}
