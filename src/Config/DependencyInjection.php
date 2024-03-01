<?php

namespace Rinha\Config;

use FrameworkX\Container;

class DependencyInjection {

    public function __construct() {
        // nao retorna nada
    }

    public static function getContainer (): Container {

        $connections = [];

        $controllers = [];

        $services = [];

        $repositories = [];

        return new Container(array_merge($connections, $controllers, $services, $repositories));
    }
}
