<?php

namespace Rinha\Entities;

class SaldoDTO
{
    private function __construct(
        public readonly int $total,
        public readonly string $data_extrato,
        public readonly int $limite
    ){

    }

    public static function fromConta(Conta $conta): self
    {
        return new self(
            $conta->getSaldo(),
            (new \DateTimeImmutable())->format("Y-m-d\TH:i:s.u\Z"),
            $conta->limite
        );
    }

}
