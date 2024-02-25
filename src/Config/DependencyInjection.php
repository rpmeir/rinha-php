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
                $credentials = "$_ENV[MYSQL_USER]:$_ENV[MYSQL_PASSWORD]@localhost/$_ENV[MYSQL_DATABASE]";
                return (new Factory())->createLazyConnection($credentials);
            }
        ];

        $controllers = [];

        $services = [];

        $repositories = [];

        return new Container(array_merge($connections, $controllers, $services, $repositories));
    }
}
