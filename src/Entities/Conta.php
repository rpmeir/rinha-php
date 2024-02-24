<?php

namespace Rinha\Entities;

class Conta
{
    public function __construct(
        public readonly int $id,
        public readonly int $cliente_id,
        public readonly int $limite,
        private int $saldo
    ){

    }

    public function getSaldo(): int
    {
        return $this->saldo;
    }

    public function setSaldo(int $valor): void
    {
        $this->saldo = $valor;
    }
}
