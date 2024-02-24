<?php

namespace Rinha\Entities;

class SaldoDTO
{
    private function __construct(
        public readonly int $total,
        public readonly \DateTimeImmutable $data_extrato,
        public readonly string $limite
    ){

    }
}
