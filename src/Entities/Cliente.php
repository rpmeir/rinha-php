<?php

namespace Rinha\Entities;

class Cliente
{
    public function __construct(
        public readonly int $id,
        public readonly string $nome
    ){

    }
}
