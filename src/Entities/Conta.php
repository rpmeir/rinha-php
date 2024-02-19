<?php

namespace Rinha\Entities;

class Conta
{
    public function __construct(
        public readonly int $id,
        public readonly int $cliente_id,
        public readonly int $limite,
        public readonly int $saldo
    ){

    }
}
