<?php

namespace Rinha\Config;

use FrameworkX\Container;
use React\MySQL\ConnectionInterface;
use React\MySQL\Factory;

class DependencyInjection {

    public function __construct() {
        // nao retorna nada
    }

    public static function getContainer (): Container {

        $connections = [
            ConnectionInterface::class => function () {
                $credentials = 'test:test@localhost/rinha';
                return (new Factory())->createLazyConnection($credentials);
            }
        ];

        return new Container(array_merge($connections));
    }
}
