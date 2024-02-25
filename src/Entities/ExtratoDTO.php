<?php

namespace Rinha\Entities;


class ExtratoDTO
{
    public function __construct(
        public readonly SaldoDTO $saldo,
        public readonly array $ultimas_transacoes
    ){

    }
}
